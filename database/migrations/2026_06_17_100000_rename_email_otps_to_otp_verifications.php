<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('email_otps') && ! Schema::hasTable('otp_verifications')) {
            Schema::rename('email_otps', 'otp_verifications');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('otp_verifications') && ! Schema::hasTable('email_otps')) {
            Schema::rename('otp_verifications', 'email_otps');
        }
    }
};
