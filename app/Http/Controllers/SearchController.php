<?php


namespace App\Http\Controllers;


use App\Models\Course;
use App\Models\StudentRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SearchController
{
    const RESULTS_PER_PAGE = 25;

    public function __invoke(Request $request)
    {
        $coursesQuery = static::getQuery();

        $searchParams = [
            'departments' => $request->query('departments', ''),
        ];

        $filterCheckboxes = [];

        foreach ($searchParams as $model => $query) {
           $query = array_filter(explode(',', $query));

            $filterCheckboxes[$model] = $query;

           if (!empty($query)) {
               switch ($model) {
                   case 'departments':
                       $coursesQuery->whereIn('courses.department_id', $query);
                       break;
               }
           }
        }

//        dd($filterCheckboxes);

        $courses = $coursesQuery->paginate(static::RESULTS_PER_PAGE)->withQueryString();

//        dd($courses);

        $studentRegistrations = auth()
            ->user()
            ->registrations()
            ->select('student_registrations.*', 'courses.id as course_id')
            ->join('course_sections', 'course_sections.id', 'student_registrations.course_section_id')
            ->join('courses', 'courses.id', 'course_sections.course_id')
            ->join('catalogs', 'catalogs.id', 'course_sections.catalog_id')
            ->where('catalogs.is_active', 1)
            ->get()->toArray();


//        dd($studentRegistrations);

//        dd($courses[0]);

//        $courseSectionsCounts = static::sectionCountPaginate($courses);
//        $courseSectionsCounts = $courseSectionsCounts->mapWithKeys(function ($item) {
//            return [$item->id => $item->section_count];
//        });
//        $courseSectionsCounts = $courseSectionsCounts->all();
//        dd($courseSectionsCounts);


//        dd($courses);

//        dd($courses[0]);

        $departmentsCoursesCount = DB::table('departments')
            ->select('departments.id', 'departments.name', DB::raw('count(departments.id) as section_count'))
            ->join('courses', 'departments.id', '=', 'courses.department_id')
            ->join('course_sections', 'courses.id', '=', 'course_sections.course_id')
            ->join('course_section_schedules', 'course_sections.id', '=', 'course_section_schedules.course_section_id')
            ->join('catalogs', 'catalogs.id', '=', 'course_sections.catalog_id')
            ->where('catalogs.is_active', 1)
            ->groupBy('departments.name', 'departments.id')
            ->orderBy('departments.name')
            ->distinct('courses.name_shorthand')
            ->get();

//        dd($departmentsCoursesCount);

//        $catalogsCoursesCount = DB::table('catalogs')
//            ->select('catalogs.id', 'catalogs.name_full', DB::raw('count(catalogs.id) as course_count'))
//            ->join('course_sections', 'catalogs.id', '=', 'course_sections.catalog_id')
//            ->join('course_section_schedules', 'course_sections.id', '=', 'course_section_schedules.course_section_id')
//            ->where('catalogs.is_active', true)
//            ->groupBy('catalogs.name_full', 'catalogs.id')
//            ->orderBy('course_count', 'desc')
//            ->get();

        return Inertia::render('Search', get_defined_vars());
    }

    protected static function sectionCountPaginate($courses)
    {
        $builder = DB::table('course_sections')
            ->select('courses.id', DB::raw('count(course_sections.id) as section_count'))
            ->join('courses', 'courses.id', '=', 'course_sections.course_id')
            ->join('course_section_schedules', 'course_sections.id', '=', 'course_section_schedules.course_section_id')
            ->join('catalogs', 'catalogs.id', '=', 'course_sections.catalog_id')
            ->where('catalogs.is_active', true)
            ->whereIn('courses.id', $courses->pluck('id'))
            ->orderBy('courses.name_shorthand')
            ->groupBy('course_sections.course_id');

        return $builder->get();
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    protected static function getQuery()
    {
        return Course::select('courses.*')
            ->with([
                'department',
                'sections'          => function ($query) {
                    $query
                        ->select('course_sections.*')
                        // course_sections.seats -
                        ->selectRaw('(select count(user_id) from student_registrations where student_registrations.course_section_id = course_sections.id and student_registrations.status = ? ) as seats_left', ['approved']);
                },
                'sections.catalog',
                'sections.schedule' => function ($query) {
                    $query
                        ->select('course_section_schedules.*')
                        ->selectRaw('course_sections.seats - (select count(user_id) from student_registrations where student_registrations.course_section_id = course_sections.id and student_registrations.status = ? ) as seats_left', ['approved'])
                        ->join('course_sections', 'course_sections.id', '=', 'course_section_schedules.course_section_id')
                        ->join('catalogs', 'course_sections.catalog_id', '=', 'catalogs.id')
                        ->where('catalogs.is_active', true);
                },
                'sections.schedule.building',
            ])
            ->join('course_sections', 'courses.id', '=', 'course_sections.course_id')
            ->join('course_section_schedules', 'course_sections.id', '=', 'course_section_schedules.course_section_id')
            ->join('catalogs', 'course_sections.catalog_id', '=', 'catalogs.id')
            ->where('catalogs.is_active', 1)
            ->orderBy('courses.name_shorthand')
            ->distinct('courses.name_shorthand');
    }
}
