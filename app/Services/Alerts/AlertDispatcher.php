<?php

namespace App\Services\Alerts;

use App\Models\AssetWatch;
use App\Models\AlertEvent;
use Illuminate\Support\Facades\Log;
use App\Services\Alerts\TelegramBotService;

class AlertDispatcher
{
    public function __construct(protected TelegramBotService $telegram)
    {
    }

    public function dispatch(AssetWatch $watch, float $price, string $provider, array $meta = []): void
    {
        AlertEvent::create([
            'asset_watch_id' => $watch->id,
            'price' => $price,
            'timeframe' => $watch->timeframe,
            'ema_length' => $watch->ema_length,
            'triggered_at' => now(),
            'provider' => $provider,
            'meta' => $meta,
        ]);

        $chatId = $watch->owner?->telegram_chat_id;
        if ($chatId && $watch->owner?->telegram_notifications_enabled) {
            $message = sprintf(
                '%s %s EMA%d touch @ %s (%s)',
                strtoupper($provider),
                strtoupper($watch->asset->symbol),
                $watch->ema_length,
                $price,
                strtoupper($watch->timeframe)
            );

            try {
                $this->telegram->sendMessage($chatId, $message);
            } catch (\Throwable $e) {
                Log::error('Failed to send Telegram alert', [
                    'watch_id' => $watch->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $watch->forceFill(['last_alert_at' => now()])->save();
    }
}
