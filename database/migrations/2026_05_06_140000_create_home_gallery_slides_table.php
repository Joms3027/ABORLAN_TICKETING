<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_gallery_slides', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');
            $table->text('caption');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $slides = [
            ['IMG_20260319_112116_746.jpg', 'Limestone gorge and sky—Palawan landscapes accessible from Aborlan.'],
            ['IMG_20260319_095538_611.jpg', 'Forest pools and cascades that draw visitors to responsibly managed sites.'],
            ['IMG_20260319_110328_673.jpg', 'Narrow canyons and cool shade—typical terrain for guided eco-tourism routes.'],
            ['IMG_20260319_102401_340.jpg', 'Guided treks help visitors experience nature while following LGU safety rules.'],
            ['IMG_20260319_120504_337.jpg', 'Natural rock arches and formations in the municipality’s protected areas.'],
            ['IMG_20260319_143841_724.jpg', 'Sunlit cliffs and greenery—plan ahead with official permits and forms.'],
            ['IMG_20260319_100514_987.jpg', 'Steep forested ridges showcasing Palawan’s biodiversity.'],
            ['IMG_20260319_120418_032.jpg', 'Trail experiences supported by municipal tourism and booking programs.'],
            ['IMG_20260319_101146_269.jpg', 'Outdoor destinations that may require health declarations and entry permits.'],
            ['IMG_20260319_102512_645.jpg', 'Responsible visitation keeps sites safe for residents and guests alike.'],
        ];

        $now = now();
        foreach ($slides as $i => [$file, $caption]) {
            DB::table('home_gallery_slides')->insert([
                'image_path' => 'images/'.$file,
                'caption' => $caption,
                'sort_order' => $i + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('home_gallery_slides');
    }
};
