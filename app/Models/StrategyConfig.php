<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrategyConfig extends Model
{
    protected $fillable = [
        'timeframe',
        'default_ema_length',
        'is_enabled',
    ];

    protected $casts = [
        'default_ema_length' => 'integer',
        'is_enabled' => 'boolean',
    ];
}
