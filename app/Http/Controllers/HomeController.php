<?php

namespace App\Http\Controllers;

use App\Services\HomePageCache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $payload = HomePageCache::payload();

        return view('home', [
            'gallerySlides' => $payload['gallerySlides'],
            'heroImageUrl' => $payload['heroImageUrl'],
        ]);
    }
}
