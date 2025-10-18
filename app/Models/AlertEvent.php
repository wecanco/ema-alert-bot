<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlertEvent extends Model
{
    protected $fillable = [
        'asset_watch_id',
        'price',
        'timeframe',
        'ema_length',
        'triggered_at',
        'provider',
        'meta',
    ];

    protected $casts = [
        'price' => 'decimal:8',
        'triggered_at' => 'datetime',
        'meta' => 'array',
    ];

    public function watch(): BelongsTo
    {
        return $this->belongsTo(AssetWatch::class, 'asset_watch_id');
    }
}
