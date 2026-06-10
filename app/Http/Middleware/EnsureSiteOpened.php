<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSiteOpened
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('opening.enabled')) {
            return $next($request);
        }

        if ($request->routeIs('opening.*')) {
            return $next($request);
        }

        if ($request->cookie(config('opening.cookie_name'))) {
            return $next($request);
        }

        return redirect()->route('opening.index');
    }
}
