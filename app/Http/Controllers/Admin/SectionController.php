<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Catalog;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\CourseSectionSchedule;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SectionController extends Controller
{
    protected static function validateSchedule(Request $request)
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $sections = CourseSection::with([
            'catalog',
            'schedule.building',
            'course.department',
        ])
            ->select('course_sections.*')
            ->join('courses', 'courses.id', '=', 'course_sections.course_id')
            ->orderBy('courses.name_shorthand')
            ->paginate(20);


        return Inertia::render('Admin/Sections/Index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $courses = Course::all();
        $catalogs = Catalog::where('year', '>=', date('Y'))->get();

        return Inertia::render('Admin/Sections/Create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'number'     => 'required',
            'seats'      => 'nullable',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
            'faculty'    => 'nullable',
            'course_id'  => 'required|exists:courses,id',
            'catalog_id' => 'required|exists:catalogs,id',
        ]);

        $section = CourseSection::create($validated);

        return redirect()->route('admin.sections.show', $section);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\CourseSection $section
     *
     * @return \Inertia\Response
     */
    public function show(CourseSection $section)
    {
        $section->load([
            'catalog',
            'schedule.building',
            'course.department',
        ]);

        return Inertia::render('Admin/Sections/Show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\CourseSection $section
     *
     * @return \Inertia\Response
     */
    public function edit(CourseSection $section)
    {
        $section->load([
            'course',
            'catalog',
        ]);

        $courses = Course::all();
        $catalogs = Catalog::where('year', '>=', date('Y'))->get();

        return Inertia::render('Admin/Sections/Edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \App\Models\CourseSection $courseSection
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, CourseSection $section)
    {
        $validated = $request->validate([
            'number'     => 'required',
            'seats'      => 'nullable',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
            'faculty'    => 'nullable',
            'course_id'  => 'required|exists:courses,id',
            'catalog_id' => 'required|exists:catalogs,id',
        ]);

        $section->update($validated);

        return redirect()->route('admin.sections.show', $section);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\CourseSection $courseSection
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(CourseSection $courseSection)
    {
        $courseSection->delete();

        return redirect()->route('admin.sections.index');
    }

    public function scheduleCreate(CourseSection $section)
    {
        $section->load([
            'catalog',
            'course',
        ]);

        $buildings = Building::all();

        return Inertia::render('Admin/Schedules/Create', get_defined_vars());
    }

    public function scheduleStore(CourseSection $section, Request $request)
    {
        $schedule = new CourseSectionSchedule(static::validateScheduleData($request));
        $schedule['course_section_id'] = $section->id;

        $schedule->save();

        return redirect()->route('admin.sections.show', $section);
    }

    protected static function validateScheduleData($request)
    {
        $validated = $request->validate([
            'is_online'   => 'sometimes|required|boolean',
            'type'        => 'required',
            'start_time'  => 'required',
            'end_time'    => 'required',
            'days'        => 'required',
            'building_id' => 'nullable|exists:buildings,id',
            'room'        => 'nullable',
        ]);


        $days = explode(',', $validated['days']);

        $mappings = [
            'SU' => 0,
            'M'  => 1,
            'T'  => 2,
            'W'  => 3,
            'TH' => 4,
            'F'  => 5,
            'S'  => 6,
        ];

        usort($days, function ($day, $next) use ($mappings) {
            return $mappings[$day] < $mappings[$next] ? -1 : 1;
        });

        $validated['days'] = implode(' ', $days);

        if (!isset($validated['is_online'])) {
            $validated['is_online'] = false;
        }

        return $validated;
    }

    public function scheduleEdit(CourseSection $section, CourseSectionSchedule $schedule): \Inertia\Response
    {
        $section->load([
            'catalog',
            'course',
        ]);

        $buildings = Building::all();

        return Inertia::render('Admin/Schedules/Edit', get_defined_vars());
    }

    public function scheduleUpdate(CourseSection $section, CourseSectionSchedule $schedule, Request $request): \Illuminate\Http\RedirectResponse
    {
        $schedule->update(static::validateScheduleData($request));

        return redirect()->route('admin.sections.show', $section);
    }

    public function scheduleDelete(CourseSection $section, CourseSectionSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('admin.sections.show', $section);
    }
}
