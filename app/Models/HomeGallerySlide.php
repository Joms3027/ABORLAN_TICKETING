<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HomeGallerySlide extends Model
{
    public const APP_SETTING_HERO_IMAGE = 'home_hero_image_path';

    /** @deprecated Use APP_SETTING_HERO_IMAGE */
    public const DEFAULT_HERO_PATH = 'images/IMG_20260319_112116_746.jpg';

    protected $fillable = [
        'image_path',
        'caption',
        'sort_order',
    ];

    public static function urlForStoredPath(string $path): string
    {
        if (str_starts_with($path, 'home-gallery/')) {
            return Storage::disk('public')->url($path);
        }

        return asset($path);
    }

    public function publicImageUrl(): string
    {
        return self::urlForStoredPath($this->image_path);
    }

    public function deleteStoredImageIfUploaded(): void
    {
        if (str_starts_with($this->image_path, 'home-gallery/')) {
            Storage::disk('public')->delete($this->image_path);
        }
    }

    protected static function booted(): void
    {
        static::deleting(function (HomeGallerySlide $slide): void {
            $slide->deleteStoredImageIfUploaded();
        });
    }
}
