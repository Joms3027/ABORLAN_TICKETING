<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Columns that hold sensitive personal / health data and must be encrypted
     * at rest. Ciphertext is far longer than the original values, so the
     * columns are widened to TEXT/LONGTEXT before the data is encrypted.
     */
    private array $columns = [
        'contact_phone',
        'emergency_contact',
        'visitor_address',
        'members',
        'health_declarations',
    ];

    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->text('contact_phone')->nullable()->change();
            $table->text('emergency_contact')->nullable()->change();
            $table->text('visitor_address')->nullable()->change();
            $table->longText('members')->nullable()->change();
            $table->longText('health_declarations')->nullable()->change();
        });

        DB::table('bookings')->orderBy('id')->chunkById(200, function ($rows) {
            foreach ($rows as $row) {
                $updates = [];

                foreach ($this->columns as $column) {
                    $value = $row->{$column} ?? null;

                    if ($value === null || $value === '') {
                        continue;
                    }

                    if ($this->isEncrypted($value)) {
                        continue;
                    }

                    $updates[$column] = Crypt::encryptString($value);
                }

                if ($updates !== []) {
                    DB::table('bookings')->where('id', $row->id)->update($updates);
                }
            }
        });
    }

    public function down(): void
    {
        DB::table('bookings')->orderBy('id')->chunkById(200, function ($rows) {
            foreach ($rows as $row) {
                $updates = [];

                foreach ($this->columns as $column) {
                    $value = $row->{$column} ?? null;

                    if ($value === null || ! $this->isEncrypted($value)) {
                        continue;
                    }

                    $updates[$column] = Crypt::decryptString($value);
                }

                if ($updates !== []) {
                    DB::table('bookings')->where('id', $row->id)->update($updates);
                }
            }
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->string('contact_phone', 32)->nullable()->change();
            $table->string('emergency_contact')->nullable()->change();
            $table->string('visitor_address')->nullable()->change();
            $table->json('members')->nullable()->change();
            $table->json('health_declarations')->nullable()->change();
        });
    }

    private function isEncrypted(string $value): bool
    {
        try {
            Crypt::decryptString($value);

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
};
