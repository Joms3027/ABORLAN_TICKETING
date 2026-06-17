<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingFeedback;
use App\Services\BookingAvailability;
use App\Services\BookingStatusService;
use App\Services\NotificationService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        protected NotificationService $notifications,
        protected BookingStatusService $bookingStatus,
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        $today = Carbon::today()->toDateString();

        $statsRow = DB::table('bookings')
            ->leftJoin('booking_feedback', 'bookings.id', '=', 'booking_feedback.booking_id')
            ->where('bookings.user_id', $user->id)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN bookings.status = ? THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN bookings.status = ? AND bookings.hike_date > ? THEN 1 ELSE 0 END) as approved_upcoming,
                SUM(CASE
                    WHEN bookings.status IN (?, ?, ?)
                        OR (bookings.status = ? AND bookings.hike_date < ?)
                    THEN 1 ELSE 0 END) as past,
                SUM(CASE
                    WHEN booking_feedback.id IS NULL
                        AND (
                            bookings.status = ?
                            OR (bookings.status = ? AND bookings.hike_date <= ?)
                        )
                    THEN 1 ELSE 0 END) as feedback_available
            ", [
                Booking::STATUS_PENDING,
                Booking::STATUS_APPROVED,
                $today,
                Booking::STATUS_COMPLETED,
                Booking::STATUS_REJECTED,
                Booking::STATUS_CANCELLED,
                Booking::STATUS_APPROVED,
                $today,
                Booking::STATUS_COMPLETED,
                Booking::STATUS_APPROVED,
                $today,
            ])
            ->first();

        $stats = [
            'total'              => (int) ($statsRow->total ?? 0),
            'pending'            => (int) ($statsRow->pending ?? 0),
            'approved_upcoming'  => (int) ($statsRow->approved_upcoming ?? 0),
            'past'               => (int) ($statsRow->past ?? 0),
            'feedback_available' => (int) ($statsRow->feedback_available ?? 0),
        ];

        $nextHike = $user->bookings()
            ->where('status', Booking::STATUS_APPROVED)
            ->whereDate('hike_date', '>', $today)
            ->orderBy('hike_date')
            ->first();

        $bookings = $user->bookings()
            ->with('feedback')
            ->orderByDesc('hike_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('bookings.index', [
            'bookings'  => $bookings,
            'nextHike'  => $nextHike,
            'stats'     => $stats,
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
            'health_declarations' => ['required', 'array'],
            'notes'             => ['nullable', 'string', 'max:1000'],
        ], [
            'permit_rules_ack.accepted' => 'You must confirm that you have read and agree to the Nag-Atup rules and regulations.',
            'members.required'          => 'Enter details for each person in your group (as on the Visitors Entry Permit).',
            'health_declarations.required' => 'Complete the health declaration for your group before submitting.',
        ]);

        $partySize = (int) $data['party_size'];
        $healthKeys = array_merge(array_keys(config('health_declaration.checklist')), ['waiver_acknowledged']);
        $healthRules = [];
        foreach ($healthKeys as $key) {
            $healthRules["health_declarations.0.$key"] = ['accepted'];
        }
        $request->validate($healthRules, [
            'health_declarations.0.*.accepted' => 'Complete every item on the health declaration form.',
        ]);
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

        $healthDeclarations = $this->normalizeHealthDeclarations($data['health_declarations'], $members);

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
            $booking = DB::transaction(function () use ($request, $data, $hikeDate, $partySize, $trekkingDays, $members, $healthDeclarations) {
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
                    'members'              => $members,
                    'health_declarations'  => $healthDeclarations,
                    'notes'                => $data['notes'] ?? null,
                    'status'            => Booking::STATUS_PENDING,
                ]);
            });
        } catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
            return back()->withInput()->withErrors(['hike_date' => $e->getMessage()]);
        }

        $this->bookingStatus->record($booking, null, Booking::STATUS_PENDING, $request->user()->id);

        try {
            $this->notifications->sendBookingSubmitted($booking);
            $this->notifications->notifyAdminsBookingSubmitted($booking);
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('bookings.show', $booking)
            ->with('status', 'Your hiking permit booking has been submitted. Reference: '.$booking->reference_code);
    }

    public function show(Request $request, Booking $booking): View
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $booking->load(['tourGuide', 'feedback']);

        return view('bookings.show', [
            'booking' => $booking,
        ]);
    }

    public function storeFeedback(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        if (! $booking->canReceiveFeedback()) {
            return back()->withErrors(['feedback' => 'Feedback is not available for this booking.']);
        }

        $rules = [
            'rating_hospitality' => ['required', 'integer', 'min:1', 'max:5'],
            'rating_place'       => ['required', 'integer', 'min:1', 'max:5'],
            'comment'            => ['nullable', 'string', 'max:2000'],
        ];

        if ($booking->tour_guide_id) {
            $rules['rating_tour_guide'] = ['required', 'integer', 'min:1', 'max:5'];
        } else {
            $rules['rating_tour_guide'] = ['nullable', 'integer', 'min:1', 'max:5'];
        }

        $data = $request->validate($rules, [
            'rating_hospitality.required' => 'Please rate the hospitality you received.',
            'rating_tour_guide.required'  => 'Please rate your tour guide.',
            'rating_place.required'       => 'Please rate the place you visited.',
        ]);

        BookingFeedback::create([
            'booking_id'          => $booking->id,
            'user_id'             => $request->user()->id,
            'rating_hospitality'  => (int) $data['rating_hospitality'],
            'rating_tour_guide'   => isset($data['rating_tour_guide']) ? (int) $data['rating_tour_guide'] : null,
            'rating_place'        => (int) $data['rating_place'],
            'comment'             => $data['comment'] ?? null,
        ]);

        return redirect()
            ->route('bookings.show', $booking)
            ->with('status', 'Thank you! Your feedback has been submitted.');
    }

    public function cancel(Request $request, Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        if (! $booking->isCancellable()) {
            return back()->withErrors(['booking' => 'This booking can no longer be cancelled.']);
        }

        $previousStatus = $booking->status;

        $booking->update([
            'status'         => Booking::STATUS_CANCELLED,
            'decided_at'     => now(),
            'tour_guide_id'  => null,
        ]);

        $this->bookingStatus->record(
            $booking,
            $previousStatus,
            Booking::STATUS_CANCELLED,
            $request->user()->id,
        );

        try {
            $this->notifications->notifyAdminsBookingCancelled($booking->fresh(['user']));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()
            ->route('bookings.index')
            ->with('status', 'Booking '.$booking->reference_code.' has been cancelled.');
    }

    public function downloadPermit(Request $request, Booking $booking): Response
    {
        abort_unless($booking->user_id === $request->user()->id, 403);

        $pdf = Pdf::loadView('bookings.partials.permit-pdf', [
            'booking' => $booking,
        ]);

        $pdf->setPaper('letter', 'portrait');

        $filename = 'Visitors-Entry-Permit-'.$booking->reference_code.'.pdf';

        if ($request->query('preview')) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    /**
     * One group health declaration applies to the entire party.
     *
     * @param  array<int, array<string, mixed>>  $submitted
     * @param  array<int, array<string, mixed>>  $members
     * @return array<int, array<string, mixed>>
     */
    private function normalizeHealthDeclarations(array $submitted, array $members): array
    {
        $checklistKeys = array_keys(config('health_declaration.checklist'));
        $declaredAt    = now()->toIso8601String();
        $entry         = $submitted[0] ?? reset($submitted) ?: [];

        $checklist = [];
        foreach ($checklistKeys as $key) {
            $checklist[$key] = ! empty($entry[$key]);
        }

        $declaredBy = trim((string) ($entry['member_name'] ?? ($members[0]['name'] ?? '')));

        return [[
            'declared_by'         => $declaredBy !== '' ? $declaredBy : null,
            'party_size'          => count($members),
            'checklist'           => $checklist,
            'waiver_acknowledged' => ! empty($entry['waiver_acknowledged']),
            'declared_at'         => $declaredAt,
        ]];
    }
}
