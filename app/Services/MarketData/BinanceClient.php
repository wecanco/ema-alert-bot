<?php

namespace App\Services\MarketData;

use App\Services\MarketData\Contracts\MarketDataClient;
use Illuminate\Support\Facades\Http;

class BinanceClient implements MarketDataClient
{
    protected string $baseUrl;

    public function __construct(?string $baseUrl = null)
    {
        $this->baseUrl = $baseUrl ?? self::getBaseUrl();
    }

    public static function getBaseUrl(): string
    {
        return 'https://api.binance.com/api/v3';
    }

    public static function getDisplayName(): string
    {
        return 'Binance';
    }

    public static function getKey(): string
    {
        return 'binance';
    }

    public function candles(string $symbol, string $interval, int $limit = 100): array
    {
        $response = Http::get($this->baseUrl.'/klines', [
            'symbol' => $symbol,
            'interval' => $interval,
            'limit' => $limit,
        ]);

        $response->throw();

        return $response->json();
    }

    public function latestPrice(string $symbol): float
    {
        $response = Http::get($this->baseUrl.'/ticker/price', [
            'symbol' => $symbol,
        ]);

        $response->throw();

        return (float) $response['price'];
    }
}
