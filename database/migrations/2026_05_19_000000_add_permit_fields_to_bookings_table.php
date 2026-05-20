<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('visitor_address')->nullable()->after('emergency_contact');
            $table->string('purpose_of_visit')->nullable()->after('visitor_address');
            $table->string('trekking_route', 500)->nullable()->after('purpose_of_visit');
            $table->string('trekking_days', 120)->nullable()->after('trekking_route');
            $table->json('members')->nullable()->after('trekking_days');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'visitor_address',
                'purpose_of_visit',
                'trekking_route',
                'trekking_days',
                'members',
            ]);
        });
    }
};
