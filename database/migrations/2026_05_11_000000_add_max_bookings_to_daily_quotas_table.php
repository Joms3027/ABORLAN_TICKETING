<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('daily_quotas', 'max_bookings')) {
            return;
        }

        Schema::table('daily_quotas', function (Blueprint $table) {
            $table->unsignedInteger('max_bookings')->nullable()->after('slots');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('daily_quotas', 'max_bookings')) {
            return;
        }

        Schema::table('daily_quotas', function (Blueprint $table) {
            $table->dropColumn('max_bookings');
        });
    }
};
