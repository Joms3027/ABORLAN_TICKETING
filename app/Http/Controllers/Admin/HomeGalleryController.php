<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\HomeGallerySlide;
use App\Services\HomePageCache;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HomeGalleryController extends Controller
{
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    public function index(): View
    {
        $slides = HomeGallerySlide::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $heroPath = AppSetting::get(HomeGallerySlide::APP_SETTING_HERO_IMAGE) ?? '';

        return view('admin.home-gallery.index', [
            'slides' => $slides,
            'heroImagePath' => $heroPath,
            'heroImageUrl' => is_string($heroPath) && $heroPath !== ''
                ? HomeGallerySlide::urlForStoredPath($heroPath)
                : null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'caption' => ['required', 'string', 'max:2000'],
            'image' => ['required', 'file', 'max:6144', 'mimes:'.implode(',', self::ALLOWED_EXTENSIONS)],
        ]);

        $path = $request->file('image')->store('home-gallery', 'public');
        $maxOrder = HomeGallerySlide::query()->max('sort_order');

        HomeGallerySlide::query()->create([
            'image_path' => $path,
            'caption' => $data['caption'],
            'sort_order' => (int) ($maxOrder ?? 0) + 1,
        ]);

        return redirect()
            ->route('admin.homePage.index')
            ->with('status', 'Gallery slide added.');
    }

    public function update(Request $request, HomeGallerySlide $slide): RedirectResponse
    {
        $data = $request->validate([
            'caption' => ['required', 'string', 'max:2000'],
            'image' => ['nullable', 'file', 'max:6144', 'mimes:'.implode(',', self::ALLOWED_EXTENSIONS)],
        ]);

        if ($request->hasFile('image')) {
            $oldPath = $slide->image_path;
            $slide->image_path = $request->file('image')->store('home-gallery', 'public');
            if (str_starts_with($oldPath, 'home-gallery/')) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $slide->caption = $data['caption'];
        $slide->save();

        return redirect()
            ->route('admin.homePage.index')
            ->with('status', 'Gallery slide updated.');
    }

    public function destroy(HomeGallerySlide $slide): RedirectResponse
    {
        $slide->delete();

        return redirect()
            ->route('admin.homePage.index')
            ->with('status', 'Gallery slide removed.');
    }

    public function updateHero(Request $request): RedirectResponse
    {
        $request->validate([
            'hero_image' => ['nullable', 'file', 'max:8192', 'mimes:'.implode(',', self::ALLOWED_EXTENSIONS)],
            'use_default_hero' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('use_default_hero')) {
            AppSetting::put(HomeGallerySlide::APP_SETTING_HERO_IMAGE, '');
            HomePageCache::clear();

            return redirect()
                ->route('admin.homePage.index')
                ->with('status', 'Hero image reset to match the first gallery slide.');
        }

        if ($request->hasFile('hero_image')) {
            $oldHero = AppSetting::get(HomeGallerySlide::APP_SETTING_HERO_IMAGE);
            $path = $request->file('hero_image')->store('home-gallery', 'public');
            AppSetting::put(HomeGallerySlide::APP_SETTING_HERO_IMAGE, $path);
            HomePageCache::clear();
            if (is_string($oldHero) && str_starts_with($oldHero, 'home-gallery/')) {
                Storage::disk('public')->delete($oldHero);
            }

            return redirect()
                ->route('admin.homePage.index')
                ->with('status', 'Hero background image updated.');
        }

        return redirect()
            ->route('admin.homePage.index')
            ->with('status', 'No hero image changes were submitted.');
    }
}
