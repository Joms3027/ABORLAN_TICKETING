<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! $this->hasIndex('bookings', 'bookings_status_index')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->index('status');
            });
        }

        if (! $this->hasIndex('bookings', 'bookings_user_id_hike_date_index')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->index(['user_id', 'hike_date']);
            });
        }

        if (Schema::hasColumn('users', 'is_admin') && ! $this->hasIndex('users', 'users_is_admin_index')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('is_admin');
            });
        }

        if (
            Schema::hasTable('email_notifications')
            && Schema::hasColumn('email_notifications', 'created_at')
            && ! $this->hasIndex('email_notifications', 'email_notifications_created_at_index')
        ) {
            Schema::table('email_notifications', function (Blueprint $table) {
                $table->index('created_at');
            });
        }
    }

    public function down(): void
    {
        if ($this->hasIndex('bookings', 'bookings_status_index')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropIndex(['status']);
            });
        }

        if ($this->hasIndex('bookings', 'bookings_user_id_hike_date_index')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->dropIndex(['user_id', 'hike_date']);
            });
        }

        if ($this->hasIndex('users', 'users_is_admin_index')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex(['is_admin']);
            });
        }

        if ($this->hasIndex('email_notifications', 'email_notifications_created_at_index')) {
            Schema::table('email_notifications', function (Blueprint $table) {
                $table->dropIndex(['created_at']);
            });
        }
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        if (! Schema::hasTable($table)) {
            return false;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            $indexes = collect(DB::select("PRAGMA index_list('{$table}')"))
                ->pluck('name');

            return $indexes->contains($indexName);
        }

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            $indexes = collect(DB::select('SHOW INDEX FROM `'.$table.'`'))
                ->pluck('Key_name')
                ->unique();

            return $indexes->contains($indexName);
        }

        return false;
    }
};
