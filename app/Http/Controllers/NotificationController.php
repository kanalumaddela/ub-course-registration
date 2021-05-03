<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        // todo
    }

    public function view(DatabaseNotification $notification)
    {
        if ($notification->unread()) {
            $notification->markAsRead();
        }

        if (isset($notification->data['url']) && !empty($notification->data['url'])) {
            return redirect($notification->data['url']);
        }

        return redirect()->back();
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
