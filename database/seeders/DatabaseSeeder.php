<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->admin()->create([
            'email' => 'admin@example.com',
            'telegram_notifications_enabled' => false,
        ]);

        $this->call([
            TimeframeSeeder::class,
            StrategyConfigSeeder::class,
            IntegrationSeeder::class,
        ]);
    }
}
