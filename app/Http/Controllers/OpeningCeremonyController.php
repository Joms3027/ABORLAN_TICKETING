<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class OpeningCeremonyController extends Controller
{
    public function index(): View
    {
        $timezone = config('opening.timezone');
        $opensAt = Carbon::parse(config('opening.opens_at'), $timezone);

        return view('opening.index', [
            'opensAtIso' => $opensAt->toIso8601String(),
            'siteName' => config('app.name'),
            'homeUrl' => url('/'),
            'logoUrl' => asset('images/Logo.png'),
        ]);
    }

    public function complete(Request $request): JsonResponse
    {
        if (! config('opening.enabled')) {
            return response()->json(['ok' => true]);
        }

        $timezone = config('opening.timezone');
        $opensAt = Carbon::parse(config('opening.opens_at'), $timezone);

        if ($opensAt->isFuture()) {
            return response()->json([
                'ok' => false,
                'message' => 'The official opening time has not been reached yet.',
            ], 403);
        }

        return response()->json(['ok' => true])->cookie(
            config('opening.cookie_name'),
            '1',
            config('opening.cookie_minutes')
        );
    }
}
