<?php

namespace App\Services\MarketData;

use App\Services\MarketData\Contracts\MarketDataClient;
use Illuminate\Support\Facades\Http;

class OkxClient implements MarketDataClient
{
    protected string $baseUrl;

    public function __construct(?string $baseUrl = null)
    {
        $this->baseUrl = $baseUrl ?? self::getBaseUrl();
    }

    public static function getBaseUrl(): string
    {
        return 'https://www.okx.com/api/v5';
    }

    public static function getDisplayName(): string
    {
        return 'OKX';
    }

    public static function getKey(): string
    {
        return 'okx';
    }

    public function candles(string $symbol, string $interval, int $limit = 100): array
    {
        $response = Http::get($this->baseUrl.'/market/candles', [
            'instId' => $this->normalizeSymbol($symbol),
            'bar' => $this->mapInterval($interval),
            'limit' => $limit,
        ]);

        $response->throw();

        return $this->transformCandles($response->json('data', []));
    }

    public function latestPrice(string $symbol): float
    {
        $response = Http::get($this->baseUrl.'/market/ticker', [
            'instId' => $this->normalizeSymbol($symbol),
        ]);

        $response->throw();

        return (float) ($response->json('data.0.last') ?? 0);
    }

    protected function normalizeSymbol(string $symbol): string
    {
        return strtoupper($symbol);
    }

    protected function mapInterval(string $interval): string
    {
        return match ($interval) {
            '1m' => '1m',
            '3m' => '3m',
            '5m' => '5m',
            '15m' => '15m',
            '30m' => '30m',
            '1h' => '1H',
            '2h' => '2H',
            '4h' => '4H',
            '6h' => '6H',
            '12h' => '12H',
            '1d' => '1D',
            '1w' => '1W',
            default => '1H',
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
