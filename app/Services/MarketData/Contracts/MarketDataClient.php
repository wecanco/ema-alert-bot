<?php

namespace App\Services\MarketData\Contracts;

interface MarketDataClient
{
    public function candles(string $symbol, string $interval, int $limit = 100): array;

    public function latestPrice(string $symbol): float;
}
