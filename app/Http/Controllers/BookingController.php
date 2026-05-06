<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BookingAvailability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = $request->user()
            ->bookings()
            ->orderByDesc('hike_date')
            ->orderByDesc('id')
            ->get();

        return view('bookings.index', [
            'bookings' => $bookings,
        ]);
    }

    public function create(): View
    {
        $availability = BookingAvailability::upcomingAvailability(30);
        $minDate      = Carbon::tomorrow()->toDateString();
        $maxDate      = Carbon::today()->addMonths(3)->toDateString();

        return view('bookings.create', [
            'availability' => $availability,
            'minDate'      => $minDate,
            'maxDate'      => $maxDate,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hike_date'         => ['required', 'date', 'after:today', 'before_or_equal:'.Carbon::today()->addMonths(3)->toDateString()],
            'party_size'        => ['required', 'integer', 'min:1', 'max:20'],
            'contact_phone'     => ['required', 'string', 'max:32'],
            'emergency_contact' => ['nullable', 'string', 'max:160'],
            'notes'             => ['nullable', 'string', 'max:1000'],
        ]);

        $hikeDate  = Carbon::parse($data['hike_date']);
        $partySize = (int) $data['party_size'];

        $quota     = BookingAvailability::quotaForDate($hikeDate);
        $remaining = BookingAvailability::remainingForDate($hikeDate);
        if ($partySize > $remaining) {
            if ($remaining === 0) {
                return back()->withInput()->withErrors([
                    'hike_date' => 'This date is full ('.$quota.' visitors per day, as set by the LGU). Choose another hike date or contact the LGU tourism desk.',
                ]);
            }

            return back()->withInput()->withErrors([
                'party_size' => 'Only '.$remaining.' visitor slot'.($remaining === 1 ? '' : 's').' remain on '.$hikeDate->format('M j, Y').' (daily limit '.$quota.', set under Admin → Daily quotas). Reduce party size or pick another date.',
            ]);
        }

        try {
            $booking = DB::transaction(function () use ($request, $data, $hikeDate, $partySize) {
                $quota  = BookingAvailability::quotaForDate($hikeDate);
                $booked = BookingAvailability::bookedSlotsForDate($hikeDate);

                if ($booked + $partySize > $quota) {
                    abort(422, 'Someone else booked the last slots before your request finished. Reduce party size or pick another date.');
                }

                $existing = Booking::query()
                    ->where('user_id', $request->user()->id)
                    ->forDate($hikeDate)
                    ->active()
                    ->exists();

                if ($existing) {
                    abort(422, 'You already have an active booking for that date.');
                }

                return Booking::create([
                    'reference_code'    => 'ATUP-'.strtoupper(Str::random(8)),
                    'user_id'           => $request->user()->id,
                    'hike_date'         => $hikeDate->toDateString(),
                    'party_size'        => $partySize,
                    'contact_phone'     => $data['contact_phone'],
                    'emergency_contact' => $data['emergency_contact'] ?? null,
                    'notes'             => $data['notes'] ?? null,
                    'status'            => Booking::STATUS_PENDING,
                ]);
            });
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return back()->withInput()->withErrors(['hike_date' => $e->getMessage()]);
        }

        return redirect()
            ->route('bookings.show', $booking)
            ->with('status', 'Your hiking permit booking has been submitted. Reference: '.$booking->reference_code);
    }

    public function show(Request $request, Booking $booking): View
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        return view('bookings.show', [
            'booking' => $booking,
        ]);
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        if (! $booking->isCancellable()) {
            return back()->withErrors(['booking' => 'This booking can no longer be cancelled.']);
        }

        $booking->update([
            'status'     => Booking::STATUS_CANCELLED,
            'decided_at' => now(),
        ]);

        return redirect()
            ->route('bookings.index')
            ->with('status', 'Booking '.$booking->reference_code.' has been cancelled.');
    }
}
