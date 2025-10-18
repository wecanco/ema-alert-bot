<?php

namespace Database\Seeders;

use App\Models\Integration;
use Illuminate\Database\Seeder;

class IntegrationSeeder extends Seeder
{
    public function run(): void
    {
        Integration::firstOrCreate(
            ['type' => 'telegram'],
            [
                'name' => 'Telegram Alerts',
                'config' => ['bot_token' => env('TELEGRAM_BOT_TOKEN')],
                'is_active' => true,
            ]
        );

        Integration::firstOrCreate(
            ['type' => 'market_data'],
            [
                'name' => 'Binance',
                'config' => ['base_url' => config('services.binance.base_url')],
                'is_active' => true,
            ]
        );
    }
}
