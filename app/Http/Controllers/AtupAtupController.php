<?php

namespace App\Http\Controllers;

use App\Services\BookingAvailability;
use Illuminate\View\View;

class AtupAtupController extends Controller
{
    public function overview(): View
    {
        $highlights = [
            [
                'image'   => 'IMG_20260319_112116_746.jpg',
                'title'   => 'Limestone gorge & sky deck',
                'caption' => 'Sunlit cliffs and open sky welcome hikers as they approach the falls.',
            ],
            [
                'image'   => 'IMG_20260319_095538_611.jpg',
                'title'   => 'Cool pools at the base',
                'caption' => 'Crystal-clear plunge pools at the foot of Atup-atup Falls.',
            ],
            [
                'image'   => 'IMG_20260319_110328_673.jpg',
                'title'   => 'Forest canyon trail',
                'caption' => 'Shaded canyon trail with ferns, vines and bird calls overhead.',
            ],
            [
                'image'   => 'IMG_20260319_102401_340.jpg',
                'title'   => 'Guided trek',
                'caption' => 'Local guides accompany every group from the Sitio Manaile entry point.',
            ],
            [
                'image'   => 'IMG_20260319_120504_337.jpg',
                'title'   => 'Natural rock arches',
                'caption' => 'Photogenic rock formations along the upper section of the trail.',
            ],
            [
                'image'   => 'IMG_20260319_143841_724.jpg',
                'title'   => 'Cliffside viewpoints',
                'caption' => 'Wide viewpoints overlooking Aborlan and the Sulu Sea on clear days.',
            ],
            [
                'image'   => 'IMG_20260319_120418_032.jpg',
                'title'   => 'Trail experiences',
                'caption' => 'Trekking experiences supported by Aborlan tourism and Barangay Culandanum.',
            ],
            [
                'image'   => 'IMG_20260319_102512_645.jpg',
                'title'   => 'Responsible visitation',
                'caption' => 'Carry-in / carry-out practices keep the falls pristine for everyone.',
            ],
        ];

        $availability = BookingAvailability::upcomingAvailability(7);

        return view('atup.overview', [
            'highlights'   => $highlights,
            'availability' => $availability,
        ]);
    }
}
