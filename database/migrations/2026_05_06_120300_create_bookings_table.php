<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code', 16)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('hike_date');
            $table->unsignedInteger('party_size')->default(1);
            $table->string('contact_phone', 32);
            $table->string('emergency_contact')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();

            $table->index(['hike_date', 'status']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
