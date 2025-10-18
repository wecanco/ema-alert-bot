<?php

namespace App\Jobs;

use App\Models\AssetWatch;
use App\Services\Alerts\AlertDispatcher;
use App\Services\Indicators\EmaCalculator;
use App\Services\MarketData\MarketDataService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckAssetWatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public AssetWatch $watch)
    {
    }

    /**
     * Create a new job instance.
     */
    public function handle(MarketDataService $marketDataService, EmaCalculator $emaCalculator, AlertDispatcher $dispatcher): void
    {
        if (! $this->watch->is_active) {
            return;
        }

        $candles = $marketDataService->syncCandles($this->watch->asset, $this->watch->timeframe, max(100, $this->watch->ema_length + 20));
        $ema = $emaCalculator->calculate($candles, $this->watch->ema_length);

        if (! $ema) {
            return;
        }

        $latestPrice = $candles->last()?->close ?? $marketDataService->latestPrice($this->watch->asset);

        if (! $latestPrice) {
            return;
        }

        if (abs($latestPrice - $ema) / $ema <= 0.001) {
            $dispatcher->dispatch($this->watch, $latestPrice, $this->watch->asset->exchange, [
                'ema' => $ema,
                'timeframe' => $this->watch->timeframe,
            ]);
        }
    }
}
