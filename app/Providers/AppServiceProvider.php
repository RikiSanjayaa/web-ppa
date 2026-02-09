<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.daisyui');
        Paginator::defaultSimpleView('vendor.pagination.simple-daisyui');

        RateLimiter::for('complaints', function (Request $request) {
            return [
                Limit::perMinute(5)->by('complaints:ip:' . $request->ip()),
                Limit::perHour(20)->by('complaints:hourly:' . $request->ip()),
            ];
        });
    }
}
