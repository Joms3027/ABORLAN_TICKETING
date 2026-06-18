<?php

namespace App\Mail;

use App\Models\EmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Admin alert when an outbound email fails to deliver after all retries.
 */
class AdminEmailDeliveryFailed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public EmailNotification $notification,
        public ?string $errorMessage = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email Delivery Failed: '.$this->notification->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.email-delivery-failed',
        );
    }
}
