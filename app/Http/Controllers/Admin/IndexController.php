<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class IndexController extends Controller
{
    public function index()
    {
        $data = [
            'registrations' => [],
            'catalogs' => [],
            'courses' => [],
            'sections' => [],
            'advisors' => [],
            'departments' => [],
            'students' => [],
        ];

        $data['courses']['total'] = DB::table('courses')->count();
        $data['sections']['total'] = DB::table('course_sections')->count();
        $data['catalogs']['total'] = DB::table('catalogs')->count();
        $data['departments']['total'] = DB::table('departments')->count();

        $data['registrations']['total'] = static::registrationQuery()->count();
        $data['registrations']['month'] = static::registrationQuery()
            ->join('course_sections', 'course_sections.id', '=', 'student_registrations.course_section_id')
            ->join('catalogs', 'catalogs.id', '=', 'course_sections.catalog_id')
            ->whereMonth('student_registrations.created_at', date('m'))
            ->count();

        $adv_count = (int) ceil(DB::select('select count(*) as dep_adv_count from (
    SELECT department_advisors.user_id FROM `department_advisors`
    group by department_advisors.user_id
) as sub')[0]->dep_adv_count);
        $data['advisors']['total'] = $adv_count;
        unset($adv_count);

        $avg_count = (int) ceil(DB::select('SELECT avg(dep_count) as avg_count from (
    select count(student_registrations.id) as dep_count FROM `student_registrations`
	join course_sections on course_sections.id = student_registrations.id
	join courses on courses.id = course_sections.course_id
	join departments on departments.id = courses.department_id
	group by departments.id
) as avg_count')[0]->avg_count);

        $data['registrations']['average_per_department'] = $avg_count;
        unset($avg_count);

        foreach ($data as $type => $info) {
            if (empty($info)) {
                continue;
            }

            foreach ($info as $k => $v) {
                $info[$k] = number_format($v);
            }

            $data[$type] = $info;
        }

        $data['courses']['topTen'] = DB::table('student_registrations')
            ->select('courses.name', 'courses.name_shorthand', DB::raw('count(student_registrations.id) as registrations'))
            ->join('course_sections', 'course_sections.id', '=', 'student_registrations.course_section_id')
            ->join('courses', 'courses.id', '=', 'course_sections.course_id')
            ->join('catalogs', 'catalogs.id', '=', 'course_sections.catalog_id')
            ->where('catalogs.is_active', 1)
            ->groupBy('courses.id')
            ->orderBy('registrations', 'desc')
            ->limit(10)
            ->get();

        $data['courses']['topTenPie'] = [
            'data' => [],
            'labels' => [],
        ];

        foreach ($data['courses']['topTen'] as $row) {
            $data['courses']['topTenPie']['data'][] = $row->registrations;
            $data['courses']['topTenPie']['labels'][] = $row->name.' ('.$row->name_shorthand.')';
        }

        $courses_semester = DB::table('student_registrations')
            ->select('catalogs.semester', DB::raw('count(student_registrations.id) as registrations'))
            ->join('course_sections', 'course_sections.id', '=', 'student_registrations.course_section_id')
            ->join('courses', 'courses.id', '=', 'course_sections.course_id')
            ->join('catalogs', 'catalogs.id', '=', 'course_sections.catalog_id')
            ->where('catalogs.is_active', 1)
            ->groupBy('catalogs.semester')
            ->get()
            ->all();

        $data['courses']['semesterPie'] = [
            'data' => array_column($courses_semester, 'registrations'),
            'labels' => array_column($courses_semester, 'semester'),
        ];


        $registrations_timeline = DB::select('SELECT MONTHNAME(created_at) as month, count(id) as month_count FROM `student_registrations` GROUP by month(created_at), MONTHNAME(created_at) order by month(created_at)');

        $data['registrations']['timeline'] = [
            'data' => array_column($registrations_timeline, 'month_count'),
            'labels' => array_column($registrations_timeline, 'month')
        ];

        $students_timeline = DB::select('SELECT MONTHNAME(created_at) as month, count(id) as month_count FROM `users` GROUP by month(created_at), MONTHNAME(created_at) order by month(created_at)');

        $data['students']['total'] = User::role('student')->count();
        $data['students']['timeline'] = [
            'data' => array_column($students_timeline, 'month_count'),
            'labels' => array_column($students_timeline, 'month')
        ];

        $department_registrations = DB::select('select departments.id, departments.name, departments.prefix, count(student_registrations.id) as registration_count FROM `student_registrations`
join course_sections on course_sections.id = student_registrations.course_section_id
join courses on courses.id = course_sections.course_id
join departments on departments.id = courses.department_id
group by departments.id, departments.name, departments.prefix
order by departments.name');
        $data['departments']['registrations'] = [
            'labels' => array_column($department_registrations, 'name'),
            'data' => array_column($department_registrations, 'registration_count')
        ];

        return Inertia::render('Admin/Index', $data);
    }

    public static function registrationQuery()
    {
        return DB::table('student_registrations');
    }

    public function advisor()
    {

    }
}
