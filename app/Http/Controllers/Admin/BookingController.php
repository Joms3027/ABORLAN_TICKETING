<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\EmailNotification;
use App\Services\BookingAvailability;
use App\Services\BookingStatusService;
use App\Services\NotificationService;
use App\Services\TourGuideAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        protected NotificationService $notifications,
        protected BookingStatusService $bookingStatus,
    ) {}

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
        $booking->load(['user', 'tourGuide', 'feedback']);

        $emailNotifications = EmailNotification::query()
            ->where('booking_id', $booking->id)
            ->latest('id')
            ->limit(5)
            ->get();

        return view('admin.bookings.show', [
            'booking'            => $booking,
            'emailNotifications' => $emailNotifications,
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

        $previousStatus = $booking->status;
        $wasApproved = $previousStatus === Booking::STATUS_APPROVED;

        $updates = [
            'status'      => $data['status'],
            'admin_notes' => $data['admin_notes'] ?? $booking->admin_notes,
            'decided_at'  => $data['status'] === Booking::STATUS_PENDING ? null : now(),
        ];

        if ($data['status'] !== Booking::STATUS_APPROVED) {
            $updates['tour_guide_id'] = null;
        }

        $booking->update($updates);

        if ($previousStatus !== $data['status']) {
            $this->bookingStatus->record(
                $booking,
                $previousStatus,
                $data['status'],
                $request->user()?->id,
                $data['admin_notes'] ?? null,
            );
        }

        $message = 'Booking '.$booking->reference_code.' updated to '.$booking->statusLabel().'.';
        $flashKey = 'status';
        $emailNotification = null;

        if ($data['status'] === Booking::STATUS_APPROVED) {
            $guide = app(TourGuideAssignment::class)->assign($booking->fresh());

            if ($guide) {
                $message .= ' Assigned tour guide: '.$guide->name.'.';
            } elseif (! $wasApproved || ! $booking->tour_guide_id) {
                $message .= ' No tour guide was available for this hike date — add guides or free up capacity in Tour guides.';
            }
        }

        if ($previousStatus !== $data['status']) {
            $booking->load(['user', 'tourGuide']);

            if (! $booking->user?->email) {
                $message .= ' Warning: No email address on file for this visitor — notification could not be sent.';
                $flashKey = 'warning';
            } elseif ($data['status'] === Booking::STATUS_APPROVED) {
                try {
                    $emailNotification = $this->notifications->sendBookingApproved($booking);
                    $message .= $this->emailDeliveryMessage($emailNotification, $booking->user->email);
                    $flashKey = $this->emailFlashKey($emailNotification);
                } catch (\Throwable $e) {
                    report($e);
                    $message .= ' Warning: Approval saved but email could not be sent — '.$e->getMessage();
                    $flashKey = 'warning';
                }
            } elseif ($data['status'] === Booking::STATUS_REJECTED) {
                try {
                    $emailNotification = $this->notifications->sendBookingRejected($booking);
                    $message .= $this->emailDeliveryMessage($emailNotification, $booking->user->email);
                    $flashKey = $this->emailFlashKey($emailNotification);
                } catch (\Throwable $e) {
                    report($e);
                    $message .= ' Warning: Rejection saved but email could not be sent — '.$e->getMessage();
                    $flashKey = 'warning';
                }
            }
        }

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with($flashKey, $message);
    }

    protected function emailDeliveryMessage(?EmailNotification $notification, string $recipientEmail): string
    {
        if ($notification === null) {
            return ' Notification email was already sent recently.';
        }

        if ($notification->status === EmailNotification::STATUS_SENT) {
            return ' Notification email sent to '.$recipientEmail.'.';
        }

        if ($notification->status === EmailNotification::STATUS_FAILED) {
            return ' Warning: Email delivery failed for '.$recipientEmail
                .($notification->error_message ? ' — '.$notification->error_message : '.');
        }

        return ' Notification email queued for '.$recipientEmail.'.';
    }

    protected function emailFlashKey(?EmailNotification $notification): string
    {
        if ($notification === null) {
            return 'status';
        }

        return $notification->status === EmailNotification::STATUS_FAILED ? 'warning' : 'status';
    }
}
