<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Stores one-time password records for email-based verification during login and registration.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_otps', function (Blueprint $table) {
            $table->id();
            $table->string('email', 160)->index();
            $table->string('otp_code');
            $table->string('purpose', 32)->default('login');
            $table->string('session_token', 64)->unique();
            $table->unsignedTinyInteger('failed_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();
            $table->timestamp('last_sent_at')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->string('ip_address', 45)->nullable();
            $table->dateTime('expires_at');
            $table->timestamps();

            $table->index(['email', 'is_verified', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_otps');
    }
};
