<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Services\BookingAvailability;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();

        $stats = [
            'pending'        => Booking::where('status', Booking::STATUS_PENDING)->count(),
            'approved'       => Booking::where('status', Booking::STATUS_APPROVED)->count(),
            'today_active'   => Booking::active()->forDate($today)->sum('party_size'),
            'total_users'    => User::where('is_admin', false)->count(),
            'total_bookings' => Booking::count(),
            'default_quota'  => BookingAvailability::defaultQuota(),
        ];

        $recentBookings = Booking::with('user')
            ->latest('id')
            ->take(8)
            ->get();

        $upcoming = BookingAvailability::upcomingAvailability(7);

        return view('admin.dashboard', [
            'stats'          => $stats,
            'recentBookings' => $recentBookings,
            'upcoming'       => $upcoming,
        ]);
    }
}
