<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\DailyQuota;
use App\Services\BookingAvailability;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class QuotaController extends Controller
{
    public function index(Request $request): View
    {
        $availability = BookingAvailability::upcomingAvailability(30);
        $defaultQuota = BookingAvailability::defaultQuota();
        $customDates  = DailyQuota::query()
            ->where('quota_date', '>=', Carbon::today()->toDateString())
            ->orderBy('quota_date')
            ->get();

        $quotaPresetDate = null;
        if ($request->filled('preset_date')) {
            $raw = (string) $request->query('preset_date');
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw)) {
                $candidate = Carbon::createFromFormat('Y-m-d', $raw)->startOfDay();
                if ($candidate->greaterThanOrEqualTo(Carbon::today())) {
                    $quotaPresetDate = $candidate->toDateString();
                }
            }
        }

        $presetSlots        = null;
        $presetMaxBookings  = null;
        $presetNote         = null;
        if ($quotaPresetDate) {
            $custom = $customDates->first(
                fn ($row) => Carbon::parse($row->quota_date)->toDateString() === $quotaPresetDate
            );
            if ($custom) {
                $presetSlots       = (int) $custom->slots;
                $presetMaxBookings = $custom->max_bookings;
                $presetNote        = $custom->note;
            } else {
                $availRow = collect($availability)->firstWhere('date', $quotaPresetDate);
                $presetSlots = $availRow['quota'] ?? $defaultQuota;
            }
        }

        $fullDays  = 0;
        $tightDays = 0;
        $openDays  = 0;
        foreach ($availability as $row) {
            if (! $row['accepts_new_bookings']) {
                $fullDays++;
            } elseif ($row['remaining'] <= max(1, (int) ($row['quota'] * 0.2))) {
                $tightDays++;
            } else {
                $openDays++;
            }
        }

        return view('admin.quotas.index', [
            'availability'       => $availability,
            'defaultQuota'       => $defaultQuota,
            'defaultMaxBookings' => BookingAvailability::defaultMaxBookings(),
            'customDates'        => $customDates,
            'quotaPresetDate'    => $quotaPresetDate,
            'presetSlots'        => $presetSlots,
            'presetMaxBookings'  => $presetMaxBookings,
            'presetNote'         => $presetNote,
            'stats'              => [
                'full_days'       => $fullDays,
                'tight_days'      => $tightDays,
                'open_days'       => $openDays,
                'overrides_total' => $customDates->count(),
            ],
        ]);
    }

    public function updateDefault(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'default_quota'        => ['required', 'integer', 'min:0', 'max:500'],
            'default_max_bookings' => ['required', 'integer', 'min:0', 'max:500'],
        ]);

        AppSetting::put(BookingAvailability::DEFAULT_QUOTA_KEY, (int) $data['default_quota']);
        AppSetting::put(BookingAvailability::DEFAULT_MAX_BOOKINGS_KEY, (int) $data['default_max_bookings']);

        $capMsg = (int) $data['default_max_bookings'] > 0
            ? (int) $data['default_max_bookings'].' bookings per day.'
            : 'no separate cap on bookings per day (only persons per day).';

        return redirect()
            ->route('admin.quotas.index')
            ->with('status', 'Defaults updated: '.$data['default_quota'].' persons per day, '.$capMsg);
    }

    public function upsertDate(Request $request): RedirectResponse
    {
        if ($request->input('max_bookings') === '' || $request->input('max_bookings') === null) {
            $request->merge(['max_bookings' => null]);
        }

        $data = $request->validate([
            'quota_date'   => ['required', 'date', 'after_or_equal:today'],
            'slots'        => ['required', 'integer', 'min:0', 'max:500'],
            'max_bookings' => ['nullable', 'integer', 'min:0', 'max:500'],
            'note'         => ['nullable', 'string', 'max:160'],
        ]);

        $maxBookings = isset($data['max_bookings']) && $data['max_bookings'] !== null
            ? (int) $data['max_bookings']
            : null;

        DailyQuota::updateOrCreate(
            ['quota_date' => Carbon::parse($data['quota_date'])->toDateString()],
            [
                'slots'        => (int) $data['slots'],
                'max_bookings' => $maxBookings,
                'note'         => $data['note'] ?? null,
            ]
        );

        return redirect()
            ->route('admin.quotas.index')
            ->with('status', 'Daily quota for '.Carbon::parse($data['quota_date'])->format('F j, Y').' has been saved.');
    }

    public function destroyDate(DailyQuota $quota): RedirectResponse
    {
        $date = Carbon::parse($quota->quota_date)->format('F j, Y');
        $quota->delete();

        return redirect()
            ->route('admin.quotas.index')
            ->with('status', 'Custom quota for '.$date.' removed. The default daily quota will apply.');
    }
}
