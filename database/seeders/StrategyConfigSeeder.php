<?php

namespace Database\Seeders;

use App\Models\StrategyConfig;
use Illuminate\Database\Seeder;

class StrategyConfigSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['timeframe' => '1m', 'default_ema_length' => 50],
            ['timeframe' => '5m', 'default_ema_length' => 50],
            ['timeframe' => '15m', 'default_ema_length' => 50],
            ['timeframe' => '1h', 'default_ema_length' => 50],
            ['timeframe' => '4h', 'default_ema_length' => 50],
            ['timeframe' => '1d', 'default_ema_length' => 50],
        ];

        foreach ($defaults as $config) {
            StrategyConfig::firstOrCreate(
                ['timeframe' => $config['timeframe']],
                ['default_ema_length' => $config['default_ema_length'], 'is_enabled' => true]
            );
        }
    }
}
