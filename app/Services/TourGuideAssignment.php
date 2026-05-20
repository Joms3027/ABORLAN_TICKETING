<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\TourGuide;
use Illuminate\Support\Carbon;

class TourGuideAssignment
{
    /**
     * Assign an available tour guide to an approved booking (one group per guide per hike date).
     */
    public function assign(Booking $booking): ?TourGuide
    {
        if ($booking->status !== Booking::STATUS_APPROVED) {
            return null;
        }

        if ($booking->tour_guide_id) {
            return $booking->tourGuide;
        }

        $date = Carbon::parse($booking->hike_date)->toDateString();

        $busyGuideIds = Booking::query()
            ->where('status', Booking::STATUS_APPROVED)
            ->whereDate('hike_date', $date)
            ->whereNotNull('tour_guide_id')
            ->when($booking->exists, fn ($q) => $q->where('id', '!=', $booking->id))
            ->pluck('tour_guide_id');

        $guide = TourGuide::query()
            ->whereNotIn('id', $busyGuideIds)
            ->orderBy('name')
            ->first();

        if ($guide) {
            $booking->forceFill(['tour_guide_id' => $guide->id])->save();
            $booking->setRelation('tourGuide', $guide);
        }

        return $guide;
    }

    public function hasAvailableGuideForDate($date): bool
    {
        $date = Carbon::parse($date)->toDateString();

        $busyCount = Booking::query()
            ->where('status', Booking::STATUS_APPROVED)
            ->whereDate('hike_date', $date)
            ->whereNotNull('tour_guide_id')
            ->distinct('tour_guide_id')
            ->count('tour_guide_id');

        return TourGuide::query()->count() > $busyCount;
    }
}
