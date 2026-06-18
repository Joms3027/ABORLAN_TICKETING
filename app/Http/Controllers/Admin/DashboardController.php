<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\EmailNotification;
use App\Models\User;
use App\Services\BookingAvailability;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();

        $stats = Cache::remember(
            'dashboard:booking_stats',
            now()->addSeconds(30),
            function () use ($today) {
                $statusCounts = Booking::query()
                    ->select('status', DB::raw('COUNT(*) as aggregate'))
                    ->groupBy('status')
                    ->pluck('aggregate', 'status');

                return [
                    'pending'        => (int) ($statusCounts[Booking::STATUS_PENDING] ?? 0),
                    'approved'       => (int) ($statusCounts[Booking::STATUS_APPROVED] ?? 0),
                    'today_active'   => (int) Booking::active()->forDate($today)->sum('party_size'),
                    'total_users'    => User::where('is_admin', false)->count(),
                    'total_bookings' => (int) $statusCounts->sum(),
                    'default_quota'      => BookingAvailability::defaultQuota(),
                    'default_max_bookings' => BookingAvailability::defaultMaxBookings(),
                ];
            }
        );

        $recentBookings = Booking::with('user')
            ->latest('id')
            ->take(8)
            ->get();

        $upcoming = BookingAvailability::upcomingAvailability(7);

        $emailStats = Cache::remember(
            'dashboard:email_stats',
            now()->addSeconds(30),
            fn () => [
                'queued' => EmailNotification::where('status', EmailNotification::STATUS_QUEUED)->count(),
                'sent_today' => EmailNotification::where('status', EmailNotification::STATUS_SENT)
                    ->whereDate('sent_at', $today)->count(),
                'failed' => EmailNotification::where('status', EmailNotification::STATUS_FAILED)->count(),
            ],
        );

        $recentEmails = EmailNotification::query()
            ->with(['user:id,name', 'booking:id,reference_code'])
            ->latest('id')
            ->take(6)
            ->get();

        return view('admin.dashboard', [
            'stats'          => $stats,
            'recentBookings' => $recentBookings,
            'upcoming'       => $upcoming,
            'emailStats'     => $emailStats,
            'recentEmails'   => $recentEmails,
        ]);
    }
}
