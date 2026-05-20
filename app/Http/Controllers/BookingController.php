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
            'emergency_contact' => ['required', 'string', 'max:160'],
            'visitor_address'   => ['required', 'string', 'max:255'],
            'purpose_of_visit'  => ['required', 'string', 'max:255'],
            'trekking_route'    => ['required', 'string', 'max:500'],
            'trekking_days'     => ['nullable', 'string', 'max:120'],
            'members'           => ['required', 'array'],
            'members.*.name'                => ['required', 'string', 'max:120'],
            'members.*.sex'                 => ['required', 'in:M,F'],
            'members.*.address'             => ['required', 'string', 'max:255'],
            'members.*.emergency_contact'   => ['required', 'string', 'max:160'],
            'members.*.body_marks'          => ['nullable', 'string', 'max:255'],
            'permit_rules_ack'  => ['accepted'],
            'notes'             => ['nullable', 'string', 'max:1000'],
        ], [
            'permit_rules_ack.accepted' => 'You must confirm that you have read and agree to the Nag-Atup rules and regulations.',
            'members.required'          => 'Enter details for each person in your group (as on the Visitors Entry Permit).',
        ]);

        $partySize = (int) $data['party_size'];
        if (count($data['members']) !== $partySize) {
            return back()->withInput()->withErrors([
                'members' => 'Provide exactly '.$partySize.' visitor record'.($partySize === 1 ? '' : 's').' — one per person in your group.',
            ]);
        }

        $trekkingDays = trim((string) ($data['trekking_days'] ?? ''));
        if ($trekkingDays === '') {
            $trekkingDays = Carbon::parse($data['hike_date'])->format('M j, Y');
        }

        $members = collect($data['members'])->map(function (array $member) {
            return [
                'name'              => trim($member['name']),
                'sex'               => $member['sex'],
                'address'           => trim($member['address']),
                'emergency_contact' => trim($member['emergency_contact']),
                'body_marks'        => trim((string) ($member['body_marks'] ?? '')) ?: null,
            ];
        })->values()->all();

        $hikeDate  = Carbon::parse($data['hike_date']);
        $partySize = (int) $data['party_size'];

        $quota     = BookingAvailability::quotaForDate($hikeDate);
        $remaining = BookingAvailability::remainingForDate($hikeDate);
        if (! BookingAvailability::dateAcceptsNewBookings($hikeDate)) {
            if ($remaining < 1) {
                return back()->withInput()->withErrors([
                    'hike_date' => 'This date is full ('.$quota.' people per day, as set by the LGU). Choose another hike date or contact the LGU tourism desk.',
                ]);
            }

            $maxBk = BookingAvailability::maxBookingsForDate($hikeDate);

            return back()->withInput()->withErrors([
                'hike_date' => 'No more bookings are accepted on '.$hikeDate->format('M j, Y').' ('.(int) $maxBk.' permit'.($maxBk === 1 ? '' : 's').' per day, set under Admin → Daily quotas). Try another date or ask the LGU to raise the limit.',
            ]);
        }

        if ($partySize > $remaining) {
            if ($remaining === 0) {
                return back()->withInput()->withErrors([
                    'hike_date' => 'This date is full ('.$quota.' people per day, as set by the LGU). Choose another hike date or contact the LGU tourism desk.',
                ]);
            }

            return back()->withInput()->withErrors([
                'party_size' => 'Only '.$remaining.' visitor slot'.($remaining === 1 ? '' : 's').' remain on '.$hikeDate->format('M j, Y').' (daily capacity '.$quota.' people, set under Admin → Daily quotas). Reduce how many people are in your group or pick another date.',
            ]);
        }

        try {
            $booking = DB::transaction(function () use ($request, $data, $hikeDate, $partySize, $trekkingDays, $members) {
                $quota  = BookingAvailability::quotaForDate($hikeDate);
                $booked = BookingAvailability::bookedSlotsForDate($hikeDate);

                if ($booked + $partySize > $quota) {
                    abort(422, 'Someone else booked the last slots before your request finished. Reduce party size or pick another date.');
                }

                $maxBookings = BookingAvailability::maxBookingsForDate($hikeDate);
                if ($maxBookings !== null) {
                    $bookingCount = BookingAvailability::bookedBookingsCountForDate($hikeDate);
                    if ($bookingCount + 1 > $maxBookings) {
                        abort(422, 'The daily limit on booking groups was reached before your request finished. Pick another date.');
                    }
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
                    'emergency_contact' => $data['emergency_contact'],
                    'visitor_address'   => $data['visitor_address'],
                    'purpose_of_visit'  => $data['purpose_of_visit'],
                    'trekking_route'    => $data['trekking_route'],
                    'trekking_days'     => $trekkingDays,
                    'members'           => $members,
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

        $booking->load('tourGuide');

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
            'status'         => Booking::STATUS_CANCELLED,
            'decided_at'     => now(),
            'tour_guide_id'  => null,
        ]);

        return redirect()
            ->route('bookings.index')
            ->with('status', 'Booking '.$booking->reference_code.' has been cancelled.');
    }
}
