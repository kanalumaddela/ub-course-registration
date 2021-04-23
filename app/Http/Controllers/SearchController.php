<?php


namespace App\Http\Controllers;


use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SearchController
{
    const RESULTS_PER_PAGE = 25;

    protected static $courseWheres = [];

    protected static $sectionWheres = [];

    protected static $courseRelations = [];


    public function __invoke(Request $request)
    {
        $coursesQuery = static::getQuery();

        static::$courseWheres = [
            ['where', 'catalogs.is_active', 1],
        ];

        static::$courseRelations = $relations = [
            'sections.catalog',
            'sections.schedule.building',
        ];

        $searchParams = [
            'catalogs'    => $request->query('catalogs', ''),
            'departments' => $request->query('departments', ''),
        ];

        $filterCheckboxes = [];

        foreach ($searchParams as $model => $queryParam) {
            $queryParam = array_filter(explode(',', $queryParam));

            $filterCheckboxes[$model] = $queryParam;

            if (!empty($queryParam)) {
                switch ($model) {
                    case 'departments':
                        static::$courseWheres[] = [
                            'whereIn',
                            'courses.department_id',
                            $queryParam,
                        ];
                        break;
                    case 'catalogs':
                        static::$courseWheres[] = [
                            'whereIn',
                            'course_sections.catalog_id',
                            $queryParam,
                        ];
                        break;
                }

                unset($whereIn);
            }
        }


        $relations['sections'] = function ($query) {
            $query
                ->select('course_sections.*')
                ->selectRaw('(select count(user_id) from student_registrations where student_registrations.course_section_id = course_sections.id and student_registrations.status in (?) ) as seats_left', ['approved'])
                ->join('courses', 'courses.id', '=', 'course_sections.course_id')
                ->join('catalogs', 'catalogs.id', '=', 'course_sections.catalog_id')
                ->where(function ($query) {
                    foreach (static::$courseWheres as $callback) {
                        call_user_func_array([$query, $callback[0]], [$callback[1], $callback[2]]);
                    }
                });
        };

        $coursesQuery->where(function ($query) {
            foreach (static::$courseWheres as $where) {
                call_user_func_array([$query, $where[0]], [$where[1], $where[2]]);
            }
        });

        $courses = $coursesQuery->paginate(static::RESULTS_PER_PAGE)->withQueryString();
        $courses->load($relations);

        unset($relations);

        $coursesCounts = static::getCoursesCounts();

        foreach ($coursesCounts as $model => $builder) {

            foreach (static::$courseWheres as $where) {
                call_user_func_array([$builder, $where[0]], [$where[1], $where[2]]);
            }

            $coursesCounts[$model] = $builder->get();
        }

        $studentRegistrations = !auth()->guest() ? auth()
            ->user()
            ->registrations()
            ->select('student_registrations.*', 'courses.id as course_id')
            ->join('course_sections', 'course_sections.id', 'student_registrations.course_section_id')
            ->join('courses', 'courses.id', 'course_sections.course_id')
            ->join('catalogs', 'catalogs.id', 'course_sections.catalog_id')
            ->where('catalogs.is_active', 1)
            ->get()->toArray() : [];

//        dd($studentRegistrations);

        return Inertia::render('Search', get_defined_vars());
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    protected static function getQuery()
    {
        return Course::select('courses.*')
            ->with([
                'department',
//                'sections.catalog',
//                'sections.schedule' => function ($query) {
//                    $query
//                        ->select('course_section_schedules.*')
//                        ->selectRaw('course_sections.seats - (select count(user_id) from student_registrations where student_registrations.course_section_id = course_sections.id and student_registrations.status = ? ) as seats_left', ['approved'])
//                        ->join('course_sections', 'course_sections.id', '=', 'course_section_schedules.course_section_id')
//                        ->join('catalogs', 'course_sections.catalog_id', '=', 'catalogs.id')
//                        ->where('catalogs.is_active', true);
//                },
//                'sections.schedule.building',
            ])
            ->join('course_sections', 'courses.id', '=', 'course_sections.course_id')
            ->join('course_section_schedules', 'course_sections.id', '=', 'course_section_schedules.course_section_id')
            ->join('catalogs', 'course_sections.catalog_id', '=', 'catalogs.id')
            ->where('catalogs.is_active', 1)
            ->orderBy('courses.name_shorthand')
            ->distinct('courses.name_shorthand');
    }

    protected static function getCoursesCounts()
    {
        return [
            'departments' => DB::table('departments')
                ->select('departments.id', 'departments.name', DB::raw('count(departments.id) as section_count'))
//                ->distinct('courses.name_shorthand')
                ->join('courses', 'departments.id', '=', 'courses.department_id')
                ->join('course_sections', 'courses.id', '=', 'course_sections.course_id')
//                ->join('course_section_schedules', 'course_sections.id', '=', 'course_section_schedules.course_section_id')
                ->join('catalogs', 'catalogs.id', '=', 'course_sections.catalog_id')
                ->where('catalogs.is_active', 1)
                ->groupBy('departments.name', 'departments.id')
                ->orderBy('departments.name'),
//                ->get(),
            'catalogs'    => DB::table('catalogs')
                ->select('catalogs.id', 'catalogs.name_full as name', DB::raw('count(catalogs.id) as section_count'))
//                ->distinct('courses.name_shorthand')
                ->join('course_sections', 'catalogs.id', '=', 'course_sections.catalog_id')
//                ->join('course_section_schedules', 'course_sections.id', '=', 'course_section_schedules.course_section_id')
                ->join('courses', 'courses.id', '=', 'course_sections.course_id')
                ->where('catalogs.is_active', 1)
                ->groupBy('catalogs.name_full', 'catalogs.id')
                ->orderBy('catalogs.semester'),
//                ->get(),
        ];
    }
}
