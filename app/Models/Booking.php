<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    public const STATUS_PENDING   = 'pending';
    public const STATUS_APPROVED  = 'approved';
    public const STATUS_REJECTED  = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'reference_code',
        'user_id',
        'hike_date',
        'party_size',
        'contact_phone',
        'emergency_contact',
        'notes',
        'status',
        'admin_notes',
        'decided_at',
    ];

    protected $casts = [
        'hike_date'  => 'date',
        'party_size' => 'integer',
        'decided_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_APPROVED]);
    }

    public function scopeForDate(Builder $query, $date): Builder
    {
        return $query->whereDate('hike_date', $date);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING   => 'Pending review',
            self::STATUS_APPROVED  => 'Approved',
            self::STATUS_REJECTED  => 'Rejected',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_COMPLETED => 'Completed',
            default                => ucfirst($this->status),
        };
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_APPROVED], true)
            && $this->hike_date
            && $this->hike_date->isFuture();
    }
}
