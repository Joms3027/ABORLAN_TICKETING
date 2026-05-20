<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use App\Models\User;
use App\Services\BookingAvailability;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AtupBookingSeeder extends Seeder
{
    public function run(): void
    {
        AppSetting::updateOrCreate(
            ['key' => BookingAvailability::DEFAULT_QUOTA_KEY],
            ['value' => '20']
        );

        AppSetting::updateOrCreate(
            ['key' => BookingAvailability::DEFAULT_MAX_BOOKINGS_KEY],
            ['value' => '0']
        );

        User::updateOrCreate(
            ['email' => 'admin@aborlan.gov.ph'],
            [
                'name'              => 'Aborlan LGU Admin',
                'phone'             => '+63 900 000 0000',
                'password'          => Hash::make('password'),
                'is_admin'          => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
