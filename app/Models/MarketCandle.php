<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketCandle extends Model
{
    protected $fillable = [
        'asset_id',
        'timeframe',
        'opens_at',
        'open',
        'high',
        'low',
        'close',
        'volume',
    ];

    protected $casts = [
        'opens_at' => 'datetime',
        'open' => 'decimal:8',
        'high' => 'decimal:8',
        'low' => 'decimal:8',
        'close' => 'decimal:8',
        'volume' => 'decimal:8',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
