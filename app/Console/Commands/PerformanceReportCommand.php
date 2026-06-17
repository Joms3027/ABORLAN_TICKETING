<?php

namespace App\Console\Commands;

use App\Services\PerformanceMetrics;
use Illuminate\Console\Command;

class PerformanceReportCommand extends Command
{
    protected $signature = 'performance:report';

    protected $description = 'Display recent request performance metrics and slow-request summary';

    public function handle(): int
    {
        $data = PerformanceMetrics::summary();
        $summary = $data['summary'];

        $this->info('Performance summary (last '.($summary['count'] ?? 0).' sampled requests)');
        $this->table(
            ['Metric', 'Value'],
            [
                ['Average response', ($summary['avg_ms'] ?? 0).' ms'],
                ['P95 response', ($summary['p95_ms'] ?? 0).' ms'],
                ['Slow requests (≥500 ms)', (string) ($summary['slow_count'] ?? 0)],
            ]
        );

        $slow = collect($data['samples'])
            ->filter(fn (array $sample) => ($sample['duration_ms'] ?? 0) >= 500)
            ->take(10);

        if ($slow->isNotEmpty()) {
            $this->newLine();
            $this->warn('Recent slow requests');
            $this->table(
                ['Method', 'Path', 'Duration', 'At'],
                $slow->map(fn (array $sample) => [
                    $sample['method'],
                    $sample['path'],
                    $sample['duration_ms'].' ms',
                    $sample['at'],
                ])->all()
            );
        }

        return self::SUCCESS;
    }
}
