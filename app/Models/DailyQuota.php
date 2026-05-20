<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyQuota extends Model
{
    protected $fillable = [
        'quota_date',
        'slots',
        'max_bookings',
        'note',
    ];

    protected $casts = [
        'quota_date'   => 'date',
        'slots'        => 'integer',
        'max_bookings' => 'integer',
    ];
}
