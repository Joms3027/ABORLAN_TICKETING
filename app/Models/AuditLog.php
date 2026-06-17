<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    public const CATEGORY_OTP = 'otp';

    public const CATEGORY_AUTH = 'auth';

    public const CATEGORY_BOOKING = 'booking';

    public const CATEGORY_EMAIL = 'email';

    public const CATEGORY_SYSTEM = 'system';

    protected $fillable = [
        'event',
        'category',
        'user_id',
        'email',
        'ip_address',
        'severity',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
