<?php


namespace App\Http\Controllers;


use App\Models\User;
use Inertia\Inertia;

class UserController
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->orderBy('id', 'desc')->paginate(20);

        return Inertia::render('Users/Index', get_defined_vars());
    }

    public function view(User $user)
    {
        return Inertia::render('Users/Profile', get_defined_vars());
    }
}
