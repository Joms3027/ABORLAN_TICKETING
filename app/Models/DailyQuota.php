<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyQuota extends Model
{
    protected $fillable = [
        'quota_date',
        'slots',
        'note',
    ];

    protected $casts = [
        'quota_date' => 'date',
        'slots'      => 'integer',
    ];
}
