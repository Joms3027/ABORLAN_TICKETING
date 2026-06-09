<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingFeedback extends Model
{
    protected $table = 'booking_feedback';

    protected $fillable = [
        'booking_id',
        'user_id',
        'rating_hospitality',
        'rating_tour_guide',
        'rating_place',
        'comment',
    ];

    protected $casts = [
        'rating_hospitality' => 'integer',
        'rating_tour_guide'  => 'integer',
        'rating_place'       => 'integer',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
