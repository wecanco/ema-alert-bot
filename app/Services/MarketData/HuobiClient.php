<?php

namespace App\Services\MarketData;

use App\Services\MarketData\Contracts\MarketDataClient;
use Illuminate\Support\Facades\Http;

class HuobiClient implements MarketDataClient
{
    protected string $baseUrl;

    public function __construct(?string $baseUrl = null)
    {
        $this->baseUrl = $baseUrl ?? self::getBaseUrl();
    }

    public static function getBaseUrl(): string
    {
        return 'https://api.huobi.pro/market';
    }

    public static function getDisplayName(): string
    {
        return 'Huobi';
    }

    public static function getKey(): string
    {
        return 'huobi';
    }

    public function candles(string $symbol, string $interval, int $limit = 100): array
    {
        $response = Http::get($this->baseUrl.'/history/kline', [
            'symbol' => $this->normalizeSymbol($symbol),
            'period' => $this->mapInterval($interval),
            'size' => $limit,
        ]);

        $response->throw();

        return $this->transformCandles($response->json('data', []));
    }

    public function latestPrice(string $symbol): float
    {
        $response = Http::get($this->baseUrl.'/detail/merged', [
            'symbol' => $this->normalizeSymbol($symbol),
        ]);

        $response->throw();

        return (float) ($response->json('tick.close') ?? 0);
    }

    protected function normalizeSymbol(string $symbol): string
    {
        return strtolower($symbol);
    }

    protected function mapInterval(string $interval): string
    {
        return match ($interval) {
            '1m' => '1min',
            '5m' => '5min',
            '15m' => '15min',
            '30m' => '30min',
            '1h' => '60min',
            '4h' => '4hour',
            '1d' => '1day',
            '1w' => '1week',
            default => '60min',
        };
    }

    protected function transformCandles(array $candles): array
    {
        return array_map(static function (array $item) {
            return [
                (int) ($item['id'] ?? 0) * 1000,
                $item['open'] ?? null,
                $item['high'] ?? null,
                $item['low'] ?? null,
                $item['close'] ?? null,
                $item['vol'] ?? null,
            ];
        }, $candles);
    }
}
