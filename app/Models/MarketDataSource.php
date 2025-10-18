<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketDataSource extends Model
{
    protected $fillable = [
        'provider',
        'config',
        'is_active',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
    ];
}
