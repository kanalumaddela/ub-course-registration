<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        ];

        $data['courses']['total'] = DB::table('courses')->count();
        $data['sections']['total'] = DB::table('course_sections')->count();
        $data['catalogs']['total'] = DB::table('catalogs')->count();
        $data['departments']['total'] = DB::table('departments')->count();

        $data['registrations']['total'] = static::registrationQuery()->count();
        $data['registrations']['year'] = static::registrationQuery()
            ->join('course_sections', 'course_sections.id', '=', 'student_registrations.course_section_id')
            ->join('catalogs', 'catalogs.id', '=', 'course_sections.catalog_id')
            ->where('catalogs.year', date('Y'))
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


        return Inertia::render('Admin/Index', $data);
    }

    public static function registrationQuery()
    {
        return DB::table('student_registrations');
    }
}
