<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $departments = Department::paginate(20);

        return Inertia::render('Admin/Departments/Index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Departments/Create');
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
            'name'   => 'required',
            'prefix' => 'required|alpha',
        ]);

        $department = Department::create($validated);

        return redirect()->route('admin.departments.show', $department);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Department $department
     *
     * @return \Inertia\Response
     */
    public function show(Department $department)
    {
        return Inertia::render('Admin/Departments/Show', get_defined_vars());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Department $department
     *
     * @return \Inertia\Response
     */
    public function edit(Department $department)
    {
        return Inertia::render('Admin/Departments/Edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Department   $department
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name'   => 'required',
            'prefix' => 'required|alpha',
        ]);

        $department->update($validated);

        return redirect()->route('admin.departments.show', $department);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Department $department
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->action([static::class, 'index']);
    }
}
