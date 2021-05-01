<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        $isSecure = $request->server('HTTPS', 'off') !== 'off' || $request->server('SERVER_PORT') == 443 || $request->server('HTTP_X_FORWARDED_PROTO') === 'https';

        if ($isSecure) {
            URL::forceScheme('https');
        }
    }

    public static function isSecure()
    {

    }
}
