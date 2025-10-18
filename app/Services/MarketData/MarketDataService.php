<?php

namespace App\Services\MarketData;

use App\Models\Asset;
use App\Models\MarketCandle;
use App\Services\MarketData\Contracts\MarketDataClient;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class MarketDataService
{
    public function __construct(public MarketDataManager $manager, public MarketDataClient $client)
    {
    }

    public function syncCandles(Asset $asset, string $timeframe, int $limit = 120): Collection
    {
        $symbol = strtoupper($asset->symbol);
        $client = $this->clientForAsset($asset);
        $raw = $client->candles($symbol, $timeframe, $limit);

        $candles = collect($raw)->map(function ($item) use ($asset, $timeframe) {
            return MarketCandle::updateOrCreate(
                [
                    'asset_id' => $asset->id,
                    'timeframe' => $timeframe,
                    'opens_at' => Carbon::createFromTimestampMs($item[0]),
                ],
                [
                    'open' => $item[1],
                    'high' => $item[2],
                    'low' => $item[3],
                    'close' => $item[4],
                    'volume' => $item[5],
                ]
            );
        });

        return $candles;
    }

    public function latestPrice(Asset $asset): float
    {
        return $this->clientForAsset($asset)->latestPrice(strtoupper($asset->symbol));
    }

    public function clientForAsset(Asset $asset): MarketDataClient
    {
        $provider = $asset->exchange ?: config('marketdata.default');

        return $this->manager->client($provider);
    }
}
