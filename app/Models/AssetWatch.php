<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetWatch extends Model
{
    protected $fillable = [
        'asset_id',
        'user_id',
        'timeframe',
        'ema_length',
        'is_active',
        'last_alert_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_alert_at' => 'datetime',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function alertEvents(): HasMany
    {
        return $this->hasMany(AlertEvent::class);
    }
}
