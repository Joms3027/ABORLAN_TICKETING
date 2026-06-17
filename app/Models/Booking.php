<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'tour_guide_id',
        'hike_date',
        'party_size',
        'contact_phone',
        'emergency_contact',
        'visitor_address',
        'purpose_of_visit',
        'trekking_route',
        'trekking_days',
        'members',
        'health_declarations',
        'notes',
        'status',
        'admin_notes',
        'decided_at',
    ];

    protected $casts = [
        'hike_date'  => 'date',
        'party_size' => 'integer',
        'contact_phone'        => 'encrypted',
        'emergency_contact'    => 'encrypted',
        'visitor_address'      => 'encrypted',
        'members'              => 'encrypted:array',
        'health_declarations'  => 'encrypted:array',
        'decided_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tourGuide(): BelongsTo
    {
        return $this->belongsTo(TourGuide::class);
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(BookingFeedback::class);
    }

    public function statusHistory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BookingStatusHistory::class);
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
        return $this->status === self::STATUS_PENDING
            && $this->hike_date
            && $this->hike_date->isFuture();
    }

    public function canReceiveFeedback(): bool
    {
        if ($this->relationLoaded('feedback') ? $this->feedback !== null : $this->feedback()->exists()) {
            return false;
        }

        if ($this->status === self::STATUS_COMPLETED) {
            return true;
        }

        if ($this->status === self::STATUS_APPROVED) {
            return $this->hike_date && $this->hike_date->lte(today());
        }

        return false;
    }

    public function feedbackOpensOn(): ?\Illuminate\Support\Carbon
    {
        if ($this->status !== self::STATUS_APPROVED || ! $this->hike_date) {
            return null;
        }

        if ($this->hike_date->lte(today())) {
            return null;
        }

        return $this->hike_date;
    }
}
