<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\DailyQuota;
use App\Models\HomeGallerySlide;
use App\Observers\BookingObserver;
use App\Services\BookingAvailability;
use App\Services\HomePageCache;
use Illuminate\Support\Facades\Cache;
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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        if ($rootUrl = config('app.url')) {
            URL::forceRootUrl($rootUrl);
        }

        Booking::observe(BookingObserver::class);

        DailyQuota::saved(function (): void {
            BookingAvailability::clearQuotaCache();
        });

        DailyQuota::deleted(function (DailyQuota $quota): void {
            BookingAvailability::clearDateCache($quota->quota_date->toDateString());
            BookingAvailability::clearQuotaCache();
        });

        HomeGallerySlide::saved(function (): void {
            HomePageCache::clear();
        });

        HomeGallerySlide::deleted(function (): void {
            HomePageCache::clear();
        });

        View::composer('layouts.admin', function ($view) {
            $view->with(
                'adminPendingCount',
                Cache::remember(
                    'admin:pending_booking_count',
                    now()->addSeconds(30),
                    fn () => Booking::where('status', Booking::STATUS_PENDING)->count()
                )
            );
        });
    }
}
