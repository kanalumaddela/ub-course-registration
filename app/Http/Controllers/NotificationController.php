<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{
    public function index()
    {
        // todo
    }

    public function clearAll()
    {
        auth()->user()->notifications()->delete();

        return redirect()->back();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return redirect()->back();
    }
}
