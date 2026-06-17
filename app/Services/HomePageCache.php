<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\HomeGallerySlide;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class HomePageCache
{
    private const CACHE_KEY = 'home_page:gallery_payload';

    private const TTL_SECONDS = 300;

    /**
     * @return array{gallerySlides: Collection<int, array{caption: ?string, url: string}>, heroImageUrl: string}
     */
    public static function payload(): array
    {
        return Cache::remember(
            self::CACHE_KEY,
            now()->addSeconds(self::TTL_SECONDS),
            function () {
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

                return [
                    'gallerySlides' => $gallerySlides,
                    'heroImageUrl' => HomeGallerySlide::urlForStoredPath($heroImagePath),
                ];
            }
        );
    }

    public static function clear(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
