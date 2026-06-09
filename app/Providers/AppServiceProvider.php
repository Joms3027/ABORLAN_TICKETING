<?php

namespace App\Providers;

use App\Models\Booking;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        if ($rootUrl = config('app.url')) {
            URL::forceRootUrl($rootUrl);
        }

        View::composer('layouts.admin', function ($view) {
            $view->with(
                'adminPendingCount',
                Booking::where('status', Booking::STATUS_PENDING)->count()
            );
        });
    }
}
