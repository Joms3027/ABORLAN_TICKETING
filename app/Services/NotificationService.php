<?php

namespace App\Services;

use App\Jobs\SendEmailNotificationJob;
use App\Mail\AdminBookingCancelled;
use App\Mail\AdminBookingSubmitted;
use App\Mail\AdminNewAccountRegistered;
use App\Mail\AdminSuspiciousLogin;
use App\Mail\AccountCreated;
use App\Mail\BookingApproved;
use App\Mail\BookingRejected;
use App\Mail\BookingSubmitted;
use App\Mail\OtpVerification;
use App\Models\EmailNotification;
use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Collection;

/**
 * Central notification service for queuing emails, logging delivery, and alerting admins.
 */
class NotificationService
{
    public function __construct(
        protected AuditLogService $auditLog,
    ) {}

    /**
     * Queue an email, persist delivery history, and return the notification record.
     *
     * @param  array<string, mixed>  $metadata
     */
    public function send(
        string $templateKey,
        string $recipientEmail,
        Mailable $mailable,
        ?int $userId = null,
        ?int $bookingId = null,
        array $metadata = [],
        bool $immediate = false,
    ): EmailNotification {
        $subject = $this->resolveSubject($templateKey, $mailable);

        $notification = EmailNotification::create([
            'template_key' => $templateKey,
            'recipient_email' => strtolower(trim($recipientEmail)),
            'subject' => $subject,
            'status' => EmailNotification::STATUS_QUEUED,
            'user_id' => $userId,
            'booking_id' => $bookingId,
            'metadata' => $metadata ?: null,
            'queued_at' => now(),
        ]);

        $job = new SendEmailNotificationJob(
            $notification->id,
            $notification->recipient_email,
            $mailable,
        );

        if ($immediate) {
            dispatch_sync($job);
            $notification->refresh();
        } else {
            SendEmailNotificationJob::dispatch(
                $notification->id,
                $notification->recipient_email,
                $mailable,
            );
        }

        $this->auditLog->log(
            'email_queued',
            category: \App\Models\AuditLog::CATEGORY_EMAIL,
            userId: $userId,
            email: $recipientEmail,
            metadata: [
                'template_key' => $templateKey,
                'notification_id' => $notification->id,
                'immediate' => $immediate,
            ],
        );

        return $notification;
    }

    /**
     * Send a notification to every administrator account.
     *
     * @param  array<string, mixed>  $metadata
     */
    public function notifyAdmins(
        string $templateKey,
        Mailable $mailable,
        array $metadata = [],
        ?int $bookingId = null,
    ): void {
        foreach ($this->adminRecipients() as $admin) {
            $this->send(
                $templateKey,
                $admin->email,
                $mailable,
                userId: $admin->id,
                bookingId: $bookingId,
                metadata: $metadata,
            );
        }
    }

    /**
     * Welcome email after account creation OTP verification.
     */
    public function sendAccountCreated(User $user): EmailNotification
    {
        return $this->send(
            'account_created',
            $user->email,
            new AccountCreated($user),
            userId: $user->id,
        );
    }

    /**
     * OTP verification email.
     */
    public function sendOtp(string $email, string $plainCode, string $purpose): EmailNotification
    {
        return $this->send(
            'otp_verification',
            $email,
            new OtpVerification($plainCode, $purpose),
            metadata: ['purpose' => $purpose],
            immediate: true,
        );
    }

    /**
     * Booking submission confirmation to the visitor.
     */
    public function sendBookingSubmitted(\App\Models\Booking $booking): EmailNotification
    {
        $booking->loadMissing('user');

        return $this->send(
            'booking_submitted',
            $booking->user->email,
            new BookingSubmitted($booking),
            userId: $booking->user_id,
            bookingId: $booking->id,
        );
    }

    /**
     * Booking approval notification to the visitor.
     */
    public function sendBookingApproved(\App\Models\Booking $booking): EmailNotification
    {
        $booking->loadMissing(['user', 'tourGuide']);

        return $this->send(
            'booking_approved',
            $booking->user->email,
            new BookingApproved($booking),
            userId: $booking->user_id,
            bookingId: $booking->id,
        );
    }

    /**
     * Booking rejection notification to the visitor.
     */
    public function sendBookingRejected(\App\Models\Booking $booking): EmailNotification
    {
        $booking->loadMissing('user');

        return $this->send(
            'booking_rejected',
            $booking->user->email,
            new BookingRejected($booking),
            userId: $booking->user_id,
            bookingId: $booking->id,
        );
    }

    /**
     * Alert administrators about a new visitor registration.
     */
    public function notifyAdminsNewAccount(User $user): void
    {
        $this->notifyAdmins(
            'admin_new_account',
            new AdminNewAccountRegistered($user),
            metadata: ['registered_user_id' => $user->id],
        );
    }

    /**
     * Alert administrators about a new booking submission.
     */
    public function notifyAdminsBookingSubmitted(\App\Models\Booking $booking): void
    {
        $booking->loadMissing('user');

        $this->notifyAdmins(
            'admin_booking_submitted',
            new AdminBookingSubmitted($booking),
            metadata: ['booking_reference' => $booking->reference_code],
            bookingId: $booking->id,
        );
    }

    /**
     * Alert administrators when a visitor cancels a booking.
     */
    public function notifyAdminsBookingCancelled(\App\Models\Booking $booking): void
    {
        $booking->loadMissing('user');

        $this->notifyAdmins(
            'admin_booking_cancelled',
            new AdminBookingCancelled($booking),
            metadata: ['booking_reference' => $booking->reference_code],
            bookingId: $booking->id,
        );
    }

    /**
     * Alert administrators about suspicious login attempts.
     */
    public function notifyAdminsSuspiciousLogin(string $email, string $ipAddress, int $retryAfterSeconds): void
    {
        $this->notifyAdmins(
            'admin_suspicious_login',
            new AdminSuspiciousLogin($email, $ipAddress, $retryAfterSeconds),
            metadata: ['target_email' => $email, 'ip_address' => $ipAddress],
        );
    }

    /**
     * @return Collection<int, User>
     */
    protected function adminRecipients(): Collection
    {
        return User::query()->where('is_admin', true)->get();
    }

  /**
     * Resolve the email subject from template metadata or the mailable envelope.
     */
    protected function resolveSubject(string $templateKey, Mailable $mailable): string
    {
        $template = EmailTemplate::query()->where('key', $templateKey)->first();

        if ($template !== null) {
            return $template->subject;
        }

        return $mailable->envelope()->subject;
    }
}
