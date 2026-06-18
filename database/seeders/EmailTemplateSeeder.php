<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Seed default email template metadata used by the notification system.
     */
    public function run(): void
    {
        $templates = [
            [
                'key' => 'account_created',
                'name' => 'Account Created Welcome',
                'subject' => 'Welcome! Your Account Has Been Successfully Created',
                'view' => 'emails.account-created',
                'description' => 'Sent after successful registration and OTP verification.',
            ],
            [
                'key' => 'otp_verification',
                'name' => 'OTP Verification',
                'subject' => 'Your Verification Code',
                'view' => 'emails.otp-verification',
                'description' => 'One-time password for login or registration.',
            ],
            [
                'key' => 'booking_submitted',
                'name' => 'Booking Request Received',
                'subject' => 'Booking Request Received',
                'view' => 'emails.booking-submitted',
                'description' => 'Confirmation when a user submits a booking request.',
            ],
            [
                'key' => 'booking_approved',
                'name' => 'Booking Approved',
                'subject' => 'Booking Request Approved',
                'view' => 'emails.booking-approved',
                'description' => 'Sent when an administrator approves a booking.',
            ],
            [
                'key' => 'booking_rejected',
                'name' => 'Booking Rejected',
                'subject' => 'Booking Request Rejected',
                'view' => 'emails.booking-rejected',
                'description' => 'Sent when an administrator rejects a booking.',
            ],
            [
                'key' => 'admin_new_account',
                'name' => 'Admin: New Account',
                'subject' => 'New User Registration',
                'view' => 'emails.admin.new-account',
                'description' => 'Notifies administrators of a new visitor account.',
            ],
            [
                'key' => 'admin_booking_submitted',
                'name' => 'Admin: Booking Submitted',
                'subject' => 'New Booking Request Submitted',
                'view' => 'emails.admin.booking-submitted',
                'description' => 'Notifies administrators of a new pending booking.',
            ],
            [
                'key' => 'admin_booking_cancelled',
                'name' => 'Admin: Booking Cancelled',
                'subject' => 'Booking Cancelled by Visitor',
                'view' => 'emails.admin.booking-cancelled',
                'description' => 'Notifies administrators when a visitor cancels a booking.',
            ],
            [
                'key' => 'admin_suspicious_login',
                'name' => 'Admin: Suspicious Login',
                'subject' => 'Security Alert: Suspicious Login Attempts',
                'view' => 'emails.admin.suspicious-login',
                'description' => 'Notifies administrators of repeated failed login attempts.',
            ],
            [
                'key' => 'admin_email_delivery_failed',
                'name' => 'Admin: Email Delivery Failed',
                'subject' => 'Email Delivery Failed',
                'view' => 'emails.admin.email-delivery-failed',
                'description' => 'Alerts administrators when an outbound email fails to deliver.',
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::query()->updateOrCreate(
                ['key' => $template['key']],
                $template + ['is_active' => true],
            );
        }
    }
}
