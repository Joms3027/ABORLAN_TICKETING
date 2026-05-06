<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\HomeGallerySlide;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $gallerySlides = HomeGallerySlide::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn (HomeGallerySlide $s) => [
                'caption' => $s->caption,
                'url' => $s->publicImageUrl(),
            ]);

        if ($gallerySlides->isEmpty()) {
            $gallerySlides = collect([[
                'caption' => 'Gallery images will appear here after they are added in Admin → Home page.',
                'url' => asset(HomeGallerySlide::DEFAULT_HERO_PATH),
            ]]);
        }

        $heroPath = AppSetting::get(HomeGallerySlide::APP_SETTING_HERO_IMAGE);
        $heroFallback = HomeGallerySlide::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->value('image_path');
        $heroImagePath = is_string($heroPath) && $heroPath !== ''
            ? $heroPath
            : ($heroFallback ?? HomeGallerySlide::DEFAULT_HERO_PATH);
        $heroImageUrl = HomeGallerySlide::urlForStoredPath($heroImagePath);

        return view('home', [
            'gallerySlides' => $gallerySlides,
            'heroImageUrl' => $heroImageUrl,
        ]);
    }
}
