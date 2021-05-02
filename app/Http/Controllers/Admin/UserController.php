<?php


namespace App\Http\Controllers\Admin;


use App\Models\User;
use Inertia\Inertia;

class UserController
{
    public function index()
    {
        $users = User::paginate(20);

        return Inertia::render('Admin/Users/Index', get_defined_vars());
    }

    public function view(User $user)
    {
        return Inertia::render('Admin/Users/View', get_defined_vars());
    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', get_defined_vars());
    }

    public function update(User $user)
    {
        // todo
    }
}
