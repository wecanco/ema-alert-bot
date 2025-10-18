<?php

namespace App\Services\MarketData;

use App\Services\MarketData\Contracts\MarketDataClient;

class MarketDataManager
{
    protected array $clients;

    protected array $availableClients = [
        BinanceClient::class,
        BybitClient::class,
        KucoinClient::class,
        OkxClient::class,
        HuobiClient::class,
    ];

    public function __construct()
    {
        $this->clients = [];
    }

    public function client(string $provider = null): MarketDataClient
    {
        $provider = $provider ?? config('marketdata.default', 'binance');

        if (! isset($this->clients[$provider])) {
            $this->clients[$provider] = $this->resolveClient($provider);
        }

        return $this->clients[$provider];
    }

    public function getAvailableExchanges(): array
    {
        return collect($this->availableClients)->map(function ($clientClass) {
            return [
                'key' => $clientClass::getKey(),
                'name' => $clientClass::getDisplayName(),
                'base_url' => $clientClass::getBaseUrl(),
            ];
        })->toArray();
    }

    protected function resolveClient(string $provider): MarketDataClient
    {
        $config = config("marketdata.providers.$provider");

        if (! $config || ! isset($config['class'])) {
            return new BinanceClient();
        }

        $class = $config['class'];

        return new $class();
    }
}
