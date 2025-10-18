<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timeframe extends Model
{
    protected $fillable = [
        'code',
        'label',
        'minutes',
        'is_enabled',
    ];

    protected $casts = [
        'minutes' => 'integer',
        'is_enabled' => 'boolean',
    ];
}
