<?php

namespace App\Console;

use App\Console\Commands\SyncAssetEma;
use App\Jobs\CheckEmaAlerts;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        SyncAssetEma::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:sync-asset-ema')->everyFiveMinutes();
        $schedule->job(new CheckEmaAlerts())->everyFiveMinutes();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
