<?php

namespace Database\Seeders;

use App\Models\Timeframe;
use Illuminate\Database\Seeder;

class TimeframeSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['code' => '1m', 'label' => '1 Minute', 'minutes' => 1],
            ['code' => '5m', 'label' => '5 Minutes', 'minutes' => 5],
            ['code' => '15m', 'label' => '15 Minutes', 'minutes' => 15],
            ['code' => '1h', 'label' => '1 Hour', 'minutes' => 60],
            ['code' => '4h', 'label' => '4 Hours', 'minutes' => 240],
            ['code' => '1d', 'label' => '1 Day', 'minutes' => 1440],
        ];

        foreach ($defaults as $timeframe) {
            Timeframe::firstOrCreate(['code' => $timeframe['code']], $timeframe);
        }
    }
}
