<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        // utf8mb4 index keys must fit older MySQL / MyISAM limits (191 x 4 bytes = 764 bytes)
        Schema::defaultStringLength(191);

        // Website API limits. Separate names = separate counters, so browsing
        // pages never uses up a visitor's inquiry-form allowance.
        RateLimiter::for('api-read', fn (Request $request) => Limit::perMinute(300)->by('read|'.$request->ip()));
        RateLimiter::for('api-inquiry', fn (Request $request) => Limit::perMinute(5)->by('inquiry|'.$request->ip()));
    }
}
