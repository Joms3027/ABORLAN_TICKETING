<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingAvailability;
use App\Services\TourGuideAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::query()->with(['user', 'tourGuide'])->latest('id');

        $status = $request->query('status');
        if ($status && in_array($status, [
            Booking::STATUS_PENDING,
            Booking::STATUS_APPROVED,
            Booking::STATUS_REJECTED,
            Booking::STATUS_CANCELLED,
            Booking::STATUS_COMPLETED,
        ], true)) {
            $query->where('status', $status);
        }

        if ($search = trim((string) $request->query('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('reference_code', 'like', '%'.$search.'%')
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%');
                  });
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('admin.bookings.index', [
            'bookings' => $bookings,
            'status'   => $status,
            'q'        => $search,
        ]);
    }

    public function show(Booking $booking): View
    {
        $booking->load(['user', 'tourGuide']);

        return view('admin.bookings.show', [
            'booking'            => $booking,
            'dayQuota'           => BookingAvailability::quotaForDate($booking->hike_date),
            'dayBooked'          => BookingAvailability::bookedSlotsForDate($booking->hike_date),
            'dayRemaining'       => BookingAvailability::remainingForDate($booking->hike_date),
            'dayMaxBookings'     => BookingAvailability::maxBookingsForDate($booking->hike_date),
            'dayBookingsCount'   => BookingAvailability::bookedBookingsCountForDate($booking->hike_date),
            'dayBookingsRemain'  => BookingAvailability::remainingBookingsForDate($booking->hike_date),
        ]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'status'      => ['required', 'in:pending,approved,rejected,cancelled,completed'],
            'admin_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $wasApproved = $booking->status === Booking::STATUS_APPROVED;

        $updates = [
            'status'      => $data['status'],
            'admin_notes' => $data['admin_notes'] ?? $booking->admin_notes,
            'decided_at'  => $data['status'] === Booking::STATUS_PENDING ? null : now(),
        ];

        if ($data['status'] !== Booking::STATUS_APPROVED) {
            $updates['tour_guide_id'] = null;
        }

        $booking->update($updates);

        $message = 'Booking '.$booking->reference_code.' updated to '.$booking->statusLabel().'.';

        if ($data['status'] === Booking::STATUS_APPROVED) {
            $guide = app(TourGuideAssignment::class)->assign($booking->fresh());

            if ($guide) {
                $message .= ' Assigned tour guide: '.$guide->name.'.';
            } elseif (! $wasApproved || ! $booking->tour_guide_id) {
                $message .= ' No tour guide was available for this hike date — add guides or free up capacity in Tour guides.';
            }
        }

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('status', $message);
    }
}
