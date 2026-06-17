<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Admin security alert for repeated failed login attempts.
 */
class AdminSuspiciousLogin extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $targetEmail,
        public string $ipAddress,
        public int $retryAfterSeconds,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Security Alert: Suspicious Login Attempts',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.suspicious-login',
        );
    }
}
