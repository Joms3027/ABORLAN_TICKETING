<?php

namespace App\Jobs;

use App\Models\EmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
     * Send the mailable and mark the notification record as sent or failed.
     */
    public function handle(): void
    {
        $notification = EmailNotification::query()->find($this->notificationId);

        if ($notification === null) {
            return;
        }

        $notification->increment('attempts');

        Mail::to($this->recipientEmail)->send($this->mailable);

        $notification->update([
            'status' => EmailNotification::STATUS_SENT,
            'sent_at' => now(),
            'error_message' => null,
            'failed_at' => null,
        ]);
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

        $notification->update([
            'status' => EmailNotification::STATUS_FAILED,
            'failed_at' => now(),
            'error_message' => $exception?->getMessage() ?? 'Unknown delivery error',
        ]);
    }
}
