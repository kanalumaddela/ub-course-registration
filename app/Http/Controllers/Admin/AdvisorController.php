<?php


namespace App\Http\Controllers\Admin;


use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdvisorController
{
    public function index()
    {
        $departmentAdvisors = User::role('advisor')
            ->orderBy('name')
            ->with('departments')
            ->paginate(20);

        $advisors = User::select('users.id', 'users.name')->role('advisor')->get();
        $departments = Department::all();

        return Inertia::render('Admin/Advisors', get_defined_vars());
    }

    public function view(User $user)
    {
        $user->load('departments');

        $advisor = $user;
        $departments = Department::all();

        unset($user);

        return Inertia::render('Admin/Advisor', get_defined_vars());
    }

    public function update(User $user, Request $request)
    {
        $validated = $request->validate([
            'department_ids' => 'required|exists:departments,id',
        ]);

        $user->departments()->sync($validated['department_ids']);

        return redirect()->back();
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'user_id'       => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        User::find($validated['user_id'])->departments()->syncWithoutDetaching([$validated['department']]);

        return redirect()->back();
    }
}
