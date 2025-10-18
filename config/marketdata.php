<?php

use App\Services\MarketData\BinanceClient;
use App\Services\MarketData\BybitClient;
use App\Services\MarketData\HuobiClient;
use App\Services\MarketData\KucoinClient;
use App\Services\MarketData\OkxClient;

return [
    'default' => env('MARKETDATA_PROVIDER', 'binance'),

    'providers' => [
        'binance' => [
            'class' => BinanceClient::class,
        ],
        'bybit' => [
            'class' => BybitClient::class,
        ],
        'kucoin' => [
            'class' => KucoinClient::class,
        ],
        'okx' => [
            'class' => OkxClient::class,
        ],
        'huobi' => [
            'class' => HuobiClient::class,
        ],
    ],
];
