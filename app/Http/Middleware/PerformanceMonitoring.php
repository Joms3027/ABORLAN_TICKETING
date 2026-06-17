<?php

namespace App\Http\Middleware;

use App\Services\PerformanceMetrics;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMonitoring
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        /** @var Response $response */
        $response = $next($request);

        $durationMs = (microtime(true) - $start) * 1000;

        PerformanceMetrics::record($request, $durationMs);

        if (config('app.debug')) {
            $response->headers->set('X-Response-Time', sprintf('%.2fms', $durationMs));
        }

        return $response;
    }
}
