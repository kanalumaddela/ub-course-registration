<?php


namespace App\Http\Controllers\Advisor;


use App\Http\Controllers\IndexController;
use App\Models\StudentRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class RegistrationController
{
    public function index($user_id = null)
    {
        $students = User::select('users.*')
            ->join('student_registrations', 'student_registrations.user_id', '=', 'users.id')
            ->whereIn('student_registrations.status', ['planned', 'pending'])
            ->groupBy('users.id');

        if ($user_id) {
            $students->where('users.id', $user_id);
        }

        if (!env('APP_DEBUG', false)) {
            $students->where('student_registrations.user_id', '<>', auth()->id());
        }

        if (!Gate::check('super')) {
            $students->join('course_sections', 'course_sections.id', '=', 'student_registrations.course_section_id')
                ->join('courses', 'courses.id', '=', 'course_sections.course_id')
                ->join('departments', 'departments.id', '=', 'courses.department_id')
                ->join('department_advisors', 'department_advisors.department_id', '=', 'departments.id')
                ->where('department_advisors.user_id', auth()->id());

            $students->with([
                'registrations' => function($query) {
                    $query
                        ->select('student_registrations.*')
                        ->join('course_sections', 'course_sections.id', '=', 'student_registrations.course_section_id')
                        ->join('courses', 'courses.id', '=', 'course_sections.course_id')
                        ->join('departments', 'departments.id', '=', 'courses.department_id')
                        ->join('department_advisors', 'department_advisors.department_id', '=', 'departments.id')
                        ->whereIn('student_registrations.status', ['planned', 'pending'])
                        ->where('department_advisors.user_id', auth()->id());
                },
                'registrations.courseSection.catalog',
                'registrations.courseSection.schedule',
                'registrations.courseSection.course.department',
            ]);
        } else {
            $students->with([
                'registrations' => function ($query) {
                    $query->whereIn('student_registrations.status', ['planned', 'pending']);
                },
                'registrations.courseSection.catalog',
                'registrations.courseSection.schedule',
                'registrations.courseSection.course.department',
            ]);
        }

//        $students->orderBy('student_registrations.created_at');

        $students = $students->paginate(20);

        if (count($students) > 0) {
            $forceActiveStudent = $students[0];
        }


        return Inertia::render('Advisor/Registrations', get_defined_vars());
    }

    public function view(User $user)
    {
        $student = $user;
        $student->load([
            'registrations' => function ($query) {
                $query->whereIn('student_registrations.status', ['planned', 'pending']);
            },
            'registrations.courseSection.catalog',
            'registrations.courseSection.schedule',
            'registrations.courseSection.course.department',
        ]);

        unset($user);

        return Inertia::render('Advisor/Registrations', get_defined_vars());
    }

    public function studentSchedule($student_id)
    {
        $schedules = DB::table('student_registrations')
            ->select([
                'student_registrations.id',
                'student_registrations.status',
                DB::raw('DATE_ADD(course_sections.start_date, INTERVAL -1 DAY) as start_date, DATE_ADD(course_sections.end_date, INTERVAL 1 DAY) as end_date'),
                DB::raw('concat(courses.name_shorthand, \'-\', course_sections.number) as course_name_full'),
                'course_section_schedules.days',
                'course_section_schedules.type',
                'course_section_schedules.is_online',
                'course_section_schedules.start_time',
                'course_section_schedules.end_time',
            ])
            ->join('course_sections', 'course_sections.id', '=', 'student_registrations.course_section_id')
            ->join('course_section_schedules', 'course_section_schedules.course_section_id', '=', 'course_sections.id')
            ->join('courses', 'courses.id', '=', 'course_sections.course_id')
            ->where('student_registrations.user_id', $student_id)
            ->get();

        $minMax = DB::table('course_section_schedules')
            ->selectRaw('addtime(min(course_section_schedules.start_time), \'-2:00:00\') as min_time, addtime(max(course_section_schedules.end_time), \'2:00:00\') as max_time')
            ->join('course_sections', 'course_sections.id', '=', 'course_section_schedules.course_section_id')
            ->join('student_registrations', 'student_registrations.course_section_id', '=', 'course_sections.id')
            ->where('student_registrations.user_id', $student_id)
            ->groupBy('student_registrations.user_id')
            ->first();

        $minMaxDates = DB::select('select min(course_sections.start_date) as min_date, min(course_sections.end_date) as max_date from `course_section_schedules`
inner join `course_sections` on `course_sections`.`id` = `course_section_schedules`.`course_section_id`
inner join `student_registrations` on `student_registrations`.`course_section_id` = `course_sections`.`id`
where `student_registrations`.`user_id` = ?
group by `student_registrations`.`user_id` limit 1', [$student_id]);

        if (!empty($minMaxDates)) {
            $minMaxDates = $minMaxDates[0];
        }

        $mappings = [
            'SU' => 0,
            'M'  => 1,
            'T'  => 2,
            'W'  => 3,
            'TH' => 4,
            'F'  => 5,
            'S'  => 6,
        ];

        $events = [];
        $hasOnline = false;

        foreach ($schedules as $i => $schedule) {
            $event = [
                'id'              => $i,
                'title'           => $schedule->course_name_full,
                'startRecur'      => $schedule->start_date,
                'endRecur'        => $schedule->end_date,
                'startTime'       => $schedule->start_time,
                'endTime'         => $schedule->end_time,
                'textColor'       => '#000',
                'backgroundColor' => IndexController::determineScheduleColor($schedule->status),
            ];


            if (empty($schedule->start_time)) {
                $event['allDay'] = true;

                if (!$hasOnline) {
                    $hasOnline = true;
                }
            }

            $days = !empty($schedule->days) ? explode(' ', $schedule->days) : null;

            if (!empty($days)) {
                foreach ($days as $day) {
                    $event['daysOfWeek'][] = $mappings[$day];
                }
            }

            $events[] = $event;
        }

        return [
            'hasOnline' => $hasOnline,
            'minTime'   => $minMax->min_time ?? null,
            'maxTime'   => $minMax->max_time ?? null,
            'events'    => $events,
            'dates'     => [
                'start_date' => $minMaxDates->min_date ?? null,
                'end_date'   => $minMaxDates->max_date ?? null,
            ],
        ];
    }

    public function update(StudentRegistration $studentRegistration, Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:approve,deny',
        ]);

        $studentRegistration->status = $validated['action'] === 'approve' ? 'approved' : 'denied';
        $studentRegistration->save();

        return redirect()->back();
    }
}
