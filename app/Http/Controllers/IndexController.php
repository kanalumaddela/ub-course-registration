<?php


namespace App\Http\Controllers;


use DateInterval;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PDO;

class IndexController
{
    public function index()
    {
        if (Auth::check()) {
            $sql = 'select concat(courses.name_shorthand, \'-\', course_sections.number) as course_name_full, course_section_schedules.days, course_section_schedules.type, course_section_schedules.start_time, course_section_schedules.end_time, student_registrations.status, course_sections.start_date as term_start, course_sections.end_date as term_end, student_registrations.status from courses join course_sections on course_sections.course_id = courses.id join course_section_schedules on course_section_schedules.course_section_id = course_sections.id join student_registrations on student_registrations.course_section_id = course_sections.id join catalogs on catalogs.id = course_sections.catalog_id where student_registrations.user_id = ?';
            $min_max_time_sql = 'select addtime(min(course_section_schedules.start_time), \'-2:00:00\') as min_time, addtime(max(course_section_schedules.end_time), \'2:00:00\') as max_time from courses join course_sections on course_sections.course_id = courses.id join course_section_schedules on course_section_schedules.course_section_id = course_sections.id join student_registrations on student_registrations.course_section_id = course_sections.id join catalogs on catalogs.id = course_sections.catalog_id where student_registrations.user_id = ? GROUP by student_registrations.user_id';

            $conn = DB::connection()->getPdo();

            $statement = $conn->prepare($sql);
            $statement->execute([Auth::id()]);


            $schedules = $statement->fetchAll(PDO::FETCH_ASSOC);

            $statement = $conn->prepare($min_max_time_sql);
            $statement->execute([Auth::id()]);

            $res = $statement->fetch(PDO::FETCH_ASSOC);


            if (count($schedules) > 0) {
                $calendarMinTime = $res['min_time'];
                $calendarMaxTime = $res['max_time'];

                $schedules = static::generateCalendarData($schedules);
            }
        }

        return Inertia::render('Index', get_defined_vars());
    }

    public function dashboard()
    {

    }

    protected static function generateCalendarData($schedules)
    {
        $data = [];
        $counter = 1;

        $mappings = [
            'SU' => 0,
            'M'  => 1,
            'T'  => 2,
            'W'  => 3,
            'TH' => 4,
            'F'  => 5,
            'S'  => 6,
        ];


        foreach ($schedules as $schedule) {
//            $days = json_decode($schedule['days'], true);
            $days = explode(' ', $schedule['days']);

            $event = [
                'days' => [],
            ];

            $event['id'] = $counter;
            $event['title'] = $schedule['course_name_full'];
            $event['startRecur'] = (new DateTime($schedule['term_start']))->sub(new DateInterval('P1D'))->format('Y-m-d');
            $event['endRecur'] = (new DateTime($schedule['term_end']))->add(new DateInterval('P1D'))->format('Y-m-d');
            $event['startTime'] = $schedule['start_time'];
            $event['endTime'] = $schedule['end_time'];
//            $event['eventDisplay'] = 'background';
            if ($schedule['status'] === 'pending') {
                $event['textColor'] = '#000';
                $event['backgroundColor'] = '#fde68a';
//                $event['backgroundColor'] = '#fcd34d';
            }

            if (!empty($days)) {
                foreach ($days as $day) {
                    $event['daysOfWeek'][] = $mappings[$day];
                }
            }


            $data[] = $event;

            $counter++;
        }

        return $data;
    }
}
