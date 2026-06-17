<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event', 64)->index();
            $table->string('category', 32)->default('system')->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('email', 160)->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('severity', 16)->default('info')->index();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
