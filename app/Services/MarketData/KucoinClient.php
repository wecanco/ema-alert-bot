<?php

namespace App\Services\MarketData;

use App\Services\MarketData\Contracts\MarketDataClient;
use Illuminate\Support\Facades\Http;

class KucoinClient implements MarketDataClient
{
    protected string $baseUrl;

    public function __construct(?string $baseUrl = null)
    {
        $this->baseUrl = $baseUrl ?? self::getBaseUrl();
    }

    public static function getBaseUrl(): string
    {
        return 'https://api.kucoin.com/api/v1';
    }

    public static function getDisplayName(): string
    {
        return 'KuCoin';
    }

    public static function getKey(): string
    {
        return 'kucoin';
    }

    public function candles(string $symbol, string $interval, int $limit = 100): array
    {
        $response = Http::get($this->baseUrl.'/market/candles', [
            'symbol' => $this->normalizeSymbol($symbol),
            'type' => $this->mapInterval($interval),
            'limit' => $limit,
        ]);

        $response->throw();

        return $this->transformCandles($response->json('data', []));
    }

    public function latestPrice(string $symbol): float
    {
        $response = Http::get($this->baseUrl.'/market/orderbook/level1', [
            'symbol' => $this->normalizeSymbol($symbol),
        ]);

        $response->throw();

        return (float) ($response->json('data.price') ?? 0);
    }

    protected function normalizeSymbol(string $symbol): string
    {
        return strtoupper($symbol);
    }

    protected function mapInterval(string $interval): string
    {
        return match ($interval) {
            '1m' => '1min',
            '3m' => '3min',
            '5m' => '5min',
            '15m' => '15min',
            '30m' => '30min',
            '1h' => '1hour',
            '2h' => '2hour',
            '4h' => '4hour',
            '6h' => '6hour',
            '12h' => '12hour',
            '1d' => '1day',
            '1w' => '1week',
            default => '1hour',
        };
    }

    protected function transformCandles(array $candles): array
    {
        return array_map(static function (array $item) {
            return [
                (int) ($item[0] ?? 0) * 1000,
                $item[1] ?? null,
                $item[3] ?? null,
                $item[4] ?? null,
                $item[2] ?? null,
                $item[5] ?? null,
            ];
        }, $candles);
    }
}
