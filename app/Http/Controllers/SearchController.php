<?php


namespace App\Http\Controllers;


use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SearchController
{
    public function __invoke()
    {
        $courses = static::getQuery()->paginate(25);

//        $departments = 'select departments.name, count(departments.id) as course_count from departments
//join courses on courses.department_id = departments.id
//join course_sections on course_sections.course_id = courses.id
//join catalogs on course_sections.term_id = catalogs.id
//join course_section_schedules on course_section_schedules.course_section_id = course_sections.id
//where catalogs.year = 2021
//group by departments.name';

        $departmentsCoursesCount = DB::table('departments')
            ->select('departments.id', 'departments.name', DB::raw('count(departments.id) as course_count'))
            ->join('courses', 'departments.id', '=', 'courses.department_id')
            ->join('course_sections', 'courses.id', '=', 'course_sections.course_id')
            ->join('catalogs', 'catalogs.id', '=', 'course_sections.term_id')
            ->join('course_section_schedules', 'course_sections.id', '=', 'course_section_schedules.course_section_id')
            ->where('catalogs.year', date('Y'))
            ->groupBy('departments.name', 'departments.id')
            ->orderBy('departments.name')
            ->get();

//        $catalogs = 'SELECT catalogs.id, catalogs.name_full, count(catalogs.id) as courses_count FROM `catalogs`
//join course_sections on course_sections.term_id = catalogs.id
//join course_section_schedules on course_section_schedules.course_section_id = course_sections.id
//where catalogs.year = 2021
//group by catalogs.name_full, catalogs.id
//order by courses_count desc';

        $catalogsCoursesCount = DB::table('catalogs')
            ->select('catalogs.id', 'catalogs.name_full', DB::raw('count(catalogs.id) as course_count'))
            ->join('course_sections', 'catalogs.id', '=', 'course_sections.term_id')
            ->join('course_section_schedules', 'course_sections.id', '=', 'course_section_schedules.course_section_id')
            ->where('catalogs.year', date('Y'))
            ->groupBy('catalogs.name_full', 'catalogs.id')
            ->orderBy('course_count', 'desc')
            ->get();

        return Inertia::render('Search', get_defined_vars());
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    protected static function getQuery()
    {
        return Course::select('courses.*')->distinct()->with('sections.catalog', 'sections.schedule')
            ->join('course_sections', 'courses.id', '=', 'course_sections.course_id')
            ->join('catalogs', 'course_sections.term_id', '=', 'catalogs.id')
            ->join('course_section_schedules', 'course_sections.id', '=', 'course_section_schedules.course_section_id')
            ->whereYear('catalogs.start', date('Y'))
            ->whereYear('catalogs.end', date('Y'))
            ->orderBy('courses.name_shorthand', 'asc');
    }
}
