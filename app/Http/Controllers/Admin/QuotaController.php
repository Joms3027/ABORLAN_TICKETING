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

        return view('admin.quotas.index', [
            'availability'    => $availability,
            'defaultQuota'    => $defaultQuota,
            'customDates'     => $customDates,
            'quotaPresetDate' => $quotaPresetDate,
        ]);
    }

    public function updateDefault(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'default_quota' => ['required', 'integer', 'min:0', 'max:500'],
        ]);

        AppSetting::put(BookingAvailability::DEFAULT_QUOTA_KEY, (int) $data['default_quota']);

        return redirect()
            ->route('admin.quotas.index')
            ->with('status', 'Default daily quota set to '.$data['default_quota'].' visitors per day.');
    }

    public function upsertDate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'quota_date' => ['required', 'date', 'after_or_equal:today'],
            'slots'      => ['required', 'integer', 'min:0', 'max:500'],
            'note'       => ['nullable', 'string', 'max:160'],
        ]);

        DailyQuota::updateOrCreate(
            ['quota_date' => Carbon::parse($data['quota_date'])->toDateString()],
            [
                'slots' => (int) $data['slots'],
                'note'  => $data['note'] ?? null,
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
