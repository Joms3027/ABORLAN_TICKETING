<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\Booking;
use App\Models\DailyQuota;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class BookingAvailability
{
    public const DEFAULT_QUOTA_KEY = 'default_daily_quota';

    /** App setting: max active booking groups per day; 0 = no separate cap (legacy behavior). */
    public const DEFAULT_MAX_BOOKINGS_KEY = 'default_max_bookings_per_day';

    private const BOOKED_CACHE_TTL_SECONDS = 60;

    private const QUOTA_CACHE_TTL_SECONDS = 300;

    public static function defaultQuota(): int
    {
        $value = AppSetting::getInt(self::DEFAULT_QUOTA_KEY, 20);

        return max(0, $value);
    }

    /**
     * Default cap on number of active bookings (groups) per day. 0 means unlimited.
     */
    public static function defaultMaxBookings(): int
    {
        return max(0, AppSetting::getInt(self::DEFAULT_MAX_BOOKINGS_KEY, 0));
    }

    /**
     * Max booking groups for a date, or null if there is no cap (only the persons limit applies).
     */
    public static function maxBookingsForDate(CarbonInterface|string $date): ?int
    {
        $dateKey = self::dateKey($date);

        return Cache::remember(
            self::quotaCacheKey($dateKey, 'max_bookings'),
            now()->addSeconds(self::QUOTA_CACHE_TTL_SECONDS),
            function () use ($dateKey) {
                $row = DailyQuota::query()->whereDate('quota_date', $dateKey)->first();

                if ($row && $row->max_bookings !== null) {
                    return max(0, (int) $row->max_bookings);
                }

                $def = self::defaultMaxBookings();

                return $def > 0 ? $def : null;
            }
        );
    }

    public static function quotaForDate(CarbonInterface|string $date): int
    {
        $dateKey = self::dateKey($date);

        return Cache::remember(
            self::quotaCacheKey($dateKey, 'slots'),
            now()->addSeconds(self::QUOTA_CACHE_TTL_SECONDS),
            function () use ($dateKey) {
                $row = DailyQuota::query()->whereDate('quota_date', $dateKey)->first();

                return $row ? (int) $row->slots : self::defaultQuota();
            }
        );
    }

    public static function bookedSlotsForDate(CarbonInterface|string $date): int
    {
        $dateKey = self::dateKey($date);

        return Cache::remember(
            self::bookedCacheKey($dateKey, 'slots'),
            now()->addSeconds(self::BOOKED_CACHE_TTL_SECONDS),
            fn () => (int) Booking::query()
                ->forDate($dateKey)
                ->active()
                ->sum('party_size')
        );
    }

    public static function bookedBookingsCountForDate(CarbonInterface|string $date): int
    {
        $dateKey = self::dateKey($date);

        return Cache::remember(
            self::bookedCacheKey($dateKey, 'count'),
            now()->addSeconds(self::BOOKED_CACHE_TTL_SECONDS),
            fn () => (int) Booking::query()
                ->forDate($dateKey)
                ->active()
                ->count()
        );
    }

    public static function remainingForDate(CarbonInterface|string $date): int
    {
        $remaining = self::quotaForDate($date) - self::bookedSlotsForDate($date);

        return max(0, $remaining);
    }

    /**
     * Remaining booking groups allowed, or null when there is no group cap.
     */
    public static function remainingBookingsForDate(CarbonInterface|string $date): ?int
    {
        $max = self::maxBookingsForDate($date);
        if ($max === null) {
            return null;
        }

        return max(0, $max - self::bookedBookingsCountForDate($date));
    }

    public static function dateAcceptsNewBookings(CarbonInterface|string $date): bool
    {
        if (self::remainingForDate($date) < 1) {
            return false;
        }

        $remBookings = self::remainingBookingsForDate($date);

        return $remBookings === null || $remBookings > 0;
    }

    public static function clearDateCache(string $date): void
    {
        $dateKey = self::dateKey($date);

        Cache::forget(self::quotaCacheKey($dateKey, 'slots'));
        Cache::forget(self::quotaCacheKey($dateKey, 'max_bookings'));
        Cache::forget(self::bookedCacheKey($dateKey, 'slots'));
        Cache::forget(self::bookedCacheKey($dateKey, 'count'));
        self::clearUpcomingCache();
    }

    public static function clearQuotaCache(): void
    {
        Cache::forget('booking_avail:defaults');
        self::clearUpcomingCache();
    }

    public static function clearUpcomingCache(): void
    {
        $today = Carbon::today()->toDateString();

        foreach ([7, 14, 30] as $days) {
            Cache::forget(self::upcomingCacheKey($days, $today));
        }
    }

    /**
     * @return array<int, array{
     *   date:string,
     *   label:string,
     *   quota:int,
     *   booked:int,
     *   remaining:int,
     *   custom:bool,
     *   note:?string,
     *   max_bookings:?int,
     *   bookings_booked:int,
     *   bookings_remaining:?int,
     *   accepts_new_bookings:bool,
     *   meter_pct:int
     * }>
     */
    public static function upcomingAvailability(int $days = 14): array
    {
        $start = Carbon::today();
        $cacheKey = self::upcomingCacheKey($days, $start->toDateString());

        return Cache::remember(
            $cacheKey,
            now()->addSeconds(self::BOOKED_CACHE_TTL_SECONDS),
            fn () => self::buildUpcomingAvailability($days, $start)
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function buildUpcomingAvailability(int $days, Carbon $start): array
    {
        $end = $start->copy()->addDays($days - 1);

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

        $bookingCounts = Booking::query()
            ->active()
            ->whereBetween('hike_date', [$start->toDateString(), $end->toDateString()])
            ->selectRaw('hike_date, COUNT(*) AS c')
            ->groupBy('hike_date')
            ->pluck('c', 'hike_date');

        $default       = self::defaultQuota();
        $defaultMaxBk  = self::defaultMaxBookings();
        $rows          = [];

        for ($cursor = $start->copy(); $cursor->lte($end); $cursor->addDay()) {
            $key           = $cursor->toDateString();
            $custom        = $customQuotas->get($key);
            $quota         = $custom ? (int) $custom->slots : $default;
            $booked        = (int) ($bookedTotals[$key] ?? 0);
            $remaining     = max(0, $quota - $booked);

            $maxBookings = null;
            if ($custom && $custom->max_bookings !== null) {
                $maxBookings = max(0, (int) $custom->max_bookings);
            } elseif ($defaultMaxBk > 0) {
                $maxBookings = $defaultMaxBk;
            }

            $bookingsBooked = (int) ($bookingCounts[$key] ?? 0);
            $bookingsRem    = $maxBookings === null ? null : max(0, $maxBookings - $bookingsBooked);

            $accepts = $remaining >= 1 && ($bookingsRem === null || $bookingsRem > 0);

            $pctPerson = $quota > 0 ? (int) round(100 * $remaining / $quota) : 0;
            $pctBook   = 100;
            if ($maxBookings !== null && $maxBookings > 0) {
                $pctBook = (int) round(100 * ($bookingsRem ?? 0) / $maxBookings);
            } elseif ($maxBookings !== null && $maxBookings === 0) {
                $pctBook = 0;
            }
            $meterPct = $accepts ? min($pctPerson, $pctBook) : 0;

            $rows[] = [
                'date'                 => $key,
                'label'                => $cursor->format('D, M j, Y'),
                'quota'                => $quota,
                'booked'               => $booked,
                'remaining'            => $remaining,
                'custom'               => (bool) $custom,
                'note'                 => $custom?->note,
                'max_bookings'         => $maxBookings,
                'bookings_booked'      => $bookingsBooked,
                'bookings_remaining'   => $bookingsRem,
                'accepts_new_bookings' => $accepts,
                'meter_pct'            => $meterPct,
            ];
        }

        return $rows;
    }

    private static function dateKey(CarbonInterface|string $date): string
    {
        return $date instanceof CarbonInterface
            ? $date->toDateString()
            : Carbon::parse($date)->toDateString();
    }

    private static function quotaCacheKey(string $date, string $suffix): string
    {
        return "booking_avail:quota:{$date}:{$suffix}";
    }

    private static function bookedCacheKey(string $date, string $suffix): string
    {
        return "booking_avail:booked:{$date}:{$suffix}";
    }

    private static function upcomingCacheKey(int $days, string $startDate): string
    {
        return "booking_avail:upcoming:{$startDate}:{$days}";
    }
}
