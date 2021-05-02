<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $courses = Course::orderBy('name_shorthand');

        if ($request->query('search')) {
            $search = $request->query('search');
            $courses->where('name', 'LIKE', '%'.$search.'%')
                ->orWhere('name_shorthand', 'LIKE', '%'.$search.'%');
        }

        $courses->with('department');

        $courses = $courses->paginate(18)->withQueryString();

        return Inertia::render('Admin/Courses/Index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $departments = Department::all();

        return Inertia::render('Admin/Courses/Create', get_defined_vars());
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
            'name'          => 'required',
            'number'        => 'required',
            'description'   => 'nullable',
            'credits'       => 'nullable',
            'department_id' => 'required|exists:departments,id',
        ]);

        $department = Department::find($validated['department_id']);
        $validated['name_shorthand'] = $department->prefix.'-'.$validated['number'];

        $course = Course::create($validated);

        return redirect()->route('admin.courses.show', $course);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Course $course
     *
     * @return \Inertia\Response
     */
    public function show(Course $course)
    {
        $course->load('department');

        return Inertia::render('Admin/Courses/Show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Course $course
     *
     * @return \Inertia\Response
     */
    public function edit(Course $course)
    {
        $departments = Department::all();

        return Inertia::render('Admin/Courses/Edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Course       $course
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name'          => 'required',
            'number'        => 'required',
            'description'   => 'nullable',
            'credits'       => 'nullable',
            'department_id' => 'required|exists:departments,id',
        ]);

        $department = Department::find($validated['department_id']);
        $validated['name_shorthand'] = $department->prefix.'-'.$validated['number'];

        $course->update($validated);

        return redirect()->route('admin.courses.show', $course);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Course $course
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->action([static::class, 'index']);
    }
}
