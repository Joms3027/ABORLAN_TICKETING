<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\Booking;
use App\Models\DailyQuota;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

class BookingAvailability
{
    public const DEFAULT_QUOTA_KEY = 'default_daily_quota';

    public static function defaultQuota(): int
    {
        $value = AppSetting::getInt(self::DEFAULT_QUOTA_KEY, 20);

        return max(0, $value);
    }

    public static function quotaForDate(CarbonInterface|string $date): int
    {
        $date = $date instanceof CarbonInterface ? $date : Carbon::parse($date);
        $row  = DailyQuota::query()->whereDate('quota_date', $date->toDateString())->first();

        return $row ? (int) $row->slots : self::defaultQuota();
    }

    public static function bookedSlotsForDate(CarbonInterface|string $date): int
    {
        $date = $date instanceof CarbonInterface ? $date : Carbon::parse($date);

        return (int) Booking::query()
            ->forDate($date)
            ->active()
            ->sum('party_size');
    }

    public static function remainingForDate(CarbonInterface|string $date): int
    {
        $remaining = self::quotaForDate($date) - self::bookedSlotsForDate($date);

        return max(0, $remaining);
    }

    /**
     * @return array<int, array{date:string, label:string, quota:int, booked:int, remaining:int, custom:bool, note:?string}>
     */
    public static function upcomingAvailability(int $days = 14): array
    {
        $start = Carbon::today();
        $end   = $start->copy()->addDays($days - 1);

        $customQuotas = DailyQuota::query()
            ->whereBetween('quota_date', [$start->toDateString(), $end->toDateString()])
            ->get()
            ->keyBy(fn ($row) => Carbon::parse($row->quota_date)->toDateString());

        $bookedTotals = Booking::query()
            ->active()
            ->whereBetween('hike_date', [$start->toDateString(), $end->toDateString()])
            ->selectRaw('hike_date, SUM(party_size) AS total')
            ->groupBy('hike_date')
            ->pluck('total', 'hike_date');

        $default = self::defaultQuota();
        $rows    = [];

        for ($cursor = $start->copy(); $cursor->lte($end); $cursor->addDay()) {
            $key      = $cursor->toDateString();
            $custom   = $customQuotas->get($key);
            $quota    = $custom ? (int) $custom->slots : $default;
            $booked   = (int) ($bookedTotals[$key] ?? 0);
            $rows[]   = [
                'date'      => $key,
                'label'     => $cursor->format('D, M j, Y'),
                'quota'     => $quota,
                'booked'    => $booked,
                'remaining' => max(0, $quota - $booked),
                'custom'    => (bool) $custom,
                'note'      => $custom?->note,
            ];
        }

        return $rows;
    }
}
