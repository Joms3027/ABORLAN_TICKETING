<?php

namespace App\Jobs;

use App\Models\EmailNotification;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Queued job that delivers an email and updates the notification delivery log.
 */
class SendEmailNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    /** @var array<int, int> */
    public array $backoff = [60, 300, 900];

    public function __construct(
        public int $notificationId,
        public string $recipientEmail,
        public Mailable $mailable,
    ) {}

    /**
     * Send the mailable and mark the notification record;
     */
    public function handle(): void
    {
        $notification = EmailNotification::query()->find($this->notificationId);

        if ($notification === null) {
            Log::warning('Email notification record not found', [
                'notification_id' => $this->notificationId,
            ]);

            return;
        }

        $notification->increment('attempts');
        $attempt = $notification->attempts;

        Log::info('Email delivery attempt', [
            'notification_id' => $notification->id,
            'template_key' => $notification->template_key,
            'recipient' => $this->recipientEmail,
            'subject' => $notification->subject,
            'attempt' => $attempt,
        ]);

        try {
            Mail::to($this->recipientEmail)->send($this->mailable);

            $notification->update([
                'status' => EmailNotification::STATUS_SENT,
                'sent_at' => now(),
                'error_message' => null,
                'failed_at' => null,
            ]);

            Log::info('Email delivered successfully', [
                'notification_id' => $notification->id,
                'template_key' => $notification->template_key,
                'recipient' => $this->recipientEmail,
                'subject' => $notification->subject,
                'attempt' => $attempt,
            ]);
        } catch (Throwable $e) {
            Log::error('Email delivery attempt failed', [
                'notification_id' => $notification->id,
                'template_key' => $notification->template_key,
                'recipient' => $this->recipientEmail,
                'subject' => $notification->subject,
                'attempt' => $attempt,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Mark the notification as permanently failed after all retries are exhausted.
     */
    public function failed(?Throwable $exception): void
    {
        $notification = EmailNotification::query()->find($this->notificationId);

        if ($notification === null) {
            return;
        }

        $errorMessage = $exception?->getMessage() ?? 'Unknown delivery error';

        $notification->update([
            'status' => EmailNotification::STATUS_FAILED,
            'failed_at' => now(),
            'error_message' => $errorMessage,
        ]);

        Log::error('Email delivery permanently failed', [
            'notification_id' => $notification->id,
            'template_key' => $notification->template_key,
            'recipient' => $this->recipientEmail,
            'subject' => $notification->subject,
            'attempts' => $notification->attempts,
            'error' => $errorMessage,
        ]);

        app(NotificationService::class)->alertAdminsDeliveryFailed($notification->fresh(), $errorMessage);
    }
}
