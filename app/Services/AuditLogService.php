<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;

/**
 * Persists security and OTP audit events for compliance and monitoring.
 */
class AuditLogService
{
    /**
     * Record an auditable system event.
     *
     * @param  array<string, mixed>  $metadata
     */
    public function log(
        string $event,
        string $category = AuditLog::CATEGORY_SYSTEM,
        ?int $userId = null,
        ?string $email = null,
        ?string $ipAddress = null,
        string $severity = 'info',
        array $metadata = [],
    ): AuditLog {
        $record = AuditLog::create([
            'event' => $event,
            'category' => $category,
            'user_id' => $userId,
            'email' => $email ? strtolower(trim($email)) : null,
            'ip_address' => $ipAddress,
            'severity' => $severity,
            'metadata' => $metadata ?: null,
        ]);

        Log::log($severity === 'warning' || $severity === 'error' ? $severity : 'info', 'Audit: '.$event, [
            'audit_id' => $record->id,
            'category' => $category,
            'email' => $email,
        ]);

        return $record;
    }

    /**
     * Log OTP generation for auditing.
     */
    public function logOtpGenerated(string $email, string $purpose, int $otpId, ?string $ipAddress = null): AuditLog
    {
        return $this->log(
            'otp_generated',
            AuditLog::CATEGORY_OTP,
            email: $email,
            ipAddress: $ipAddress,
            metadata: ['purpose' => $purpose, 'otp_id' => $otpId],
        );
    }

    /**
     * Log successful OTP verification.
     */
    public function logOtpVerified(string $email, string $purpose, int $otpId, ?string $ipAddress = null): AuditLog
    {
        return $this->log(
            'otp_verified',
            AuditLog::CATEGORY_OTP,
            email: $email,
            ipAddress: $ipAddress,
            metadata: ['purpose' => $purpose, 'otp_id' => $otpId],
        );
    }

    /**
     * Log a failed OTP verification attempt.
     */
    public function logOtpFailed(string $email, string $purpose, int $otpId, int $attempts, ?string $ipAddress = null): AuditLog
    {
        return $this->log(
            'otp_verification_failed',
            AuditLog::CATEGORY_OTP,
            email: $email,
            ipAddress: $ipAddress,
            severity: 'warning',
            metadata: ['purpose' => $purpose, 'otp_id' => $otpId, 'failed_attempts' => $attempts],
        );
    }

    /**
     * Log suspicious login activity and trigger admin alerts.
     */
    public function logSuspiciousLogin(string $email, string $ipAddress, int $retryAfterSeconds): AuditLog
    {
        return $this->log(
            'suspicious_login_attempts',
            AuditLog::CATEGORY_AUTH,
            email: $email,
            ipAddress: $ipAddress,
            severity: 'warning',
            metadata: ['retry_after_seconds' => $retryAfterSeconds],
        );
    }
}
