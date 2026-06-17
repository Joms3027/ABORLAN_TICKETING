<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingStatusHistory;

/**
 * Records booking status transitions for audit trails.
 */
class BookingStatusService
{
    /**
     * Persist a booking status change to history.
     */
    public function record(
        Booking $booking,
        ?string $fromStatus,
        string $toStatus,
        ?int $changedBy = null,
        ?string $notes = null,
    ): BookingStatusHistory {
        return BookingStatusHistory::create([
            'booking_id' => $booking->id,
            'changed_by' => $changedBy,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'notes' => $notes,
        ]);
    }
}
