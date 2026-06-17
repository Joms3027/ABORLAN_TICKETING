<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PerformanceMetrics
{
    private const CACHE_KEY = 'performance:recent_requests';

    private const MAX_SAMPLES = 100;

    private const SLOW_THRESHOLD_MS = 500;

    public static function record(Request $request, float $durationMs): void
    {
        $path = '/'.$request->path();
        $sample = [
            'path' => $path,
            'method' => $request->method(),
            'duration_ms' => round($durationMs, 2),
            'at' => now()->toIso8601String(),
        ];

        if ($durationMs >= self::SLOW_THRESHOLD_MS) {
            Log::channel('single')->warning('Slow request detected', $sample);
        }

        $samples = Cache::get(self::CACHE_KEY, []);
        array_unshift($samples, $sample);
        $samples = array_slice($samples, 0, self::MAX_SAMPLES);

        Cache::put(self::CACHE_KEY, $samples, now()->addHours(24));
    }

    /**
     * @return array{samples: list<array<string, mixed>>, summary: array<string, mixed>}
     */
    public static function summary(): array
    {
        $samples = Cache::get(self::CACHE_KEY, []);

        if ($samples === []) {
            return [
                'samples' => [],
                'summary' => [
                    'count' => 0,
                    'avg_ms' => 0,
                    'p95_ms' => 0,
                    'slow_count' => 0,
                ],
            ];
        }

        $durations = array_column($samples, 'duration_ms');
        sort($durations);
        $count = count($durations);
        $p95Index = (int) ceil($count * 0.95) - 1;

        return [
            'samples' => $samples,
            'summary' => [
                'count' => $count,
                'avg_ms' => round(array_sum($durations) / $count, 2),
                'p95_ms' => $durations[max(0, $p95Index)],
                'slow_count' => count(array_filter($durations, fn ($ms) => $ms >= self::SLOW_THRESHOLD_MS)),
            ],
        ];
    }
}
