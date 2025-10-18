<?php

namespace App\Services\MarketData;

use App\Services\MarketData\Contracts\MarketDataClient;
use Illuminate\Support\Facades\Http;

class BybitClient implements MarketDataClient
{
    protected string $baseUrl;

    public function __construct(?string $baseUrl = null)
    {
        $this->baseUrl = $baseUrl ?? self::getBaseUrl();
    }

    public static function getBaseUrl(): string
    {
        return 'https://api.bybit.com/v5/market';
    }

    public static function getDisplayName(): string
    {
        return 'Bybit';
    }

    public static function getKey(): string
    {
        return 'bybit';
    }

    public function candles(string $symbol, string $interval, int $limit = 100): array
    {
        $response = Http::get($this->baseUrl.'/kline', [
            'category' => 'linear',
            'symbol' => $this->normalizeSymbol($symbol),
            'interval' => $this->mapInterval($interval),
            'limit' => $limit,
        ]);

        $response->throw();

        return $this->transformCandles($response->json('result.list', []));
    }

    public function latestPrice(string $symbol): float
    {
        $response = Http::get($this->baseUrl.'/tickers', [
            'category' => 'linear',
            'symbol' => $this->normalizeSymbol($symbol),
        ]);

        $response->throw();

        return (float) ($response->json('result.list.0.lastPrice') ?? 0);
    }

    protected function normalizeSymbol(string $symbol): string
    {
        return strtoupper($symbol);
    }

    protected function mapInterval(string $interval): string
    {
        return match ($interval) {
            '1m', '3m', '5m', '15m', '30m', '1h', '2h', '4h', '6h', '12h', '1d', '1w', '1M' => $interval,
            default => '1h',
        };
    }

    protected function transformCandles(array $candles): array
    {
        return array_map(static function (array $item) {
            return [
                (int) ($item[0] ?? 0),
                $item[1] ?? null,
                $item[2] ?? null,
                $item[3] ?? null,
                $item[4] ?? null,
                $item[5] ?? null,
            ];
        }, $candles);
    }
}
