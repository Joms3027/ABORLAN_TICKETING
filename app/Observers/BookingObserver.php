<?php

namespace App\Observers;

use App\Models\Booking;
use App\Services\BookingAvailability;
use Illuminate\Support\Facades\Cache;

class BookingObserver
{
    public function saved(Booking $booking): void
    {
        $this->invalidate($booking);
    }

    public function deleted(Booking $booking): void
    {
        $this->invalidate($booking);
    }

    private function invalidate(Booking $booking): void
    {
        if ($booking->hike_date) {
            BookingAvailability::clearDateCache($booking->hike_date->toDateString());
        }

        Cache::forget('admin:pending_booking_count');
        Cache::forget('dashboard:booking_stats');
    }
}
