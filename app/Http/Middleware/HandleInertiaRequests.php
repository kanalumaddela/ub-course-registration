<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function share(Request $request)
    {
        $data = [];

        if (auth()->check()) {
            $user_id = auth()->id();

            $data['user.roles'] = auth()->user()->getRoleNames();
//            $data['user.permissions'] = auth()->user()->getAllPermissions();
            $data['user.is_super'] = Gate::check('super');

//            $test = (new User)->roles()


            if (Route::currentRouteName() !== 'messages.index') {
                $data['user.unreadMessageCount'] = \Illuminate\Support\Facades\DB::table('messages')
                    ->join('conversations', 'conversations.id', '=', 'messages.conversation_id')
                    ->whereNull('messages.read_at')
                    ->where('messages.user_id', '<>', $user_id)
                    ->where(function ($query) use ($user_id) {
                        $query
                            ->where('conversations.author_id', $user_id)
                            ->orWhere('conversations.recipient_id', $user_id);
                    })->count();
            }

//            $data['user.unreadMessageCount'] = \Illuminate\Support\Facades\DB::table('messages')
//                ->join('conversations', 'conversations.id', '=', 'messages.conversation_id')
//                ->whereNull('messages.read_at')
//                ->where('messages.user_id', '<>', $user_id)
//                ->where(function ($query) use ($user_id) {
//                    $query
//                        ->where('conversations.author_id', $user_id)
//                        ->orWhere('conversations.recipient_id', $user_id);
//                })->count();

            $data['user.notifications'] = function () use ($request) {
                return [
                    'all'         => !auth()->guest() ? $request->user()->notifications()->limit(5)->get() : [],
                    'totalCount'  => !auth()->guest() ? $request->user()->notifications()->count() : 0,
                    'unreadCount' => !auth()->guest() ? $request->user()->unreadNotifications()->count() : [],
//                    'unreadCount' => 7,
                ];
            };

            unset($user_id);
        }

        return array_merge(parent::share($request), $data);
    }
}
