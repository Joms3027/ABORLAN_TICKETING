<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TourGuide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class TourGuideController extends Controller
{
    public function index(): View
    {
        $guides = TourGuide::query()->orderBy('name')->get();

        $upcomingAssignments = Booking::query()
            ->with(['user', 'tourGuide'])
            ->where('status', Booking::STATUS_APPROVED)
            ->whereDate('hike_date', '>=', Carbon::today())
            ->whereNotNull('tour_guide_id')
            ->orderBy('hike_date')
            ->orderBy('id')
            ->get()
            ->groupBy('tour_guide_id');

        $unassignedApproved = Booking::query()
            ->with('user')
            ->where('status', Booking::STATUS_APPROVED)
            ->whereDate('hike_date', '>=', Carbon::today())
            ->whereNull('tour_guide_id')
            ->orderBy('hike_date')
            ->get();

        $today = Carbon::today()->toDateString();
        $busyTodayIds = Booking::query()
            ->where('status', Booking::STATUS_APPROVED)
            ->whereDate('hike_date', $today)
            ->whereNotNull('tour_guide_id')
            ->pluck('tour_guide_id');

        $upcomingCount = $upcomingAssignments->flatten()->count();

        return view('admin.tour-guides.index', [
            'guides'                => $guides,
            'upcomingAssignments'   => $upcomingAssignments,
            'unassignedApproved'    => $unassignedApproved,
            'upcomingCount'         => $upcomingCount,
            'availableTodayCount'   => $guides->whereNotIn('id', $busyTodayIds)->count(),
            'busyTodayIds'          => $busyTodayIds,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'age'  => ['required', 'integer', 'min:18', 'max:80'],
        ]);

        TourGuide::create($data);

        return redirect()
            ->route('admin.tour-guides.index')
            ->withFragment('roster')
            ->with('status', 'Tour guide '.$data['name'].' has been added.');
    }

    public function update(Request $request, TourGuide $tourGuide): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'age'  => ['required', 'integer', 'min:18', 'max:80'],
        ]);

        $tourGuide->update($data);

        return redirect()
            ->route('admin.tour-guides.index')
            ->withFragment('roster')
            ->with('status', 'Tour guide '.$tourGuide->name.' has been updated.');
    }

    public function destroy(TourGuide $tourGuide): RedirectResponse
    {
        $hasUpcoming = $tourGuide->bookings()
            ->where('status', Booking::STATUS_APPROVED)
            ->whereDate('hike_date', '>=', Carbon::today())
            ->exists();

        if ($hasUpcoming) {
            return redirect()
                ->route('admin.tour-guides.index')
                ->withFragment('roster')
                ->withErrors([
                    'tour_guide' => 'Cannot delete '.$tourGuide->name.' — they still have upcoming approved groups assigned.',
                ]);
        }

        $name = $tourGuide->name;
        $tourGuide->delete();

        return redirect()
            ->route('admin.tour-guides.index')
            ->withFragment('roster')
            ->with('status', 'Tour guide '.$name.' has been removed.');
    }
}
