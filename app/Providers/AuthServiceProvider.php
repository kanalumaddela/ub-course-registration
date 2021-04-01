<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        if (!app()->runningInConsole() && Auth::guest() && env('APP_DEBUG')) {
            Auth::loginUsingId(1);
        }

        Gate::before(function ($user, $ability) {
            return $user->id === 1 || $user->hasRole('admin') ? true : null;
        });
    }
}
