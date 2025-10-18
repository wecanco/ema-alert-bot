<?php

namespace App\Console\Commands;

use App\Jobs\CheckAssetWatch;
use App\Models\AssetWatch;
use Illuminate\Console\Command;

class SyncAssetEma extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-asset-ema {--asset=} {--timeframe=} {--exchange=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync EMA data and dispatch alerts for configured watches';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = AssetWatch::query()->with(['asset']);

        if ($assetSymbol = $this->option('asset')) {
            $query->whereHas('asset', fn ($q) => $q->where('symbol', $assetSymbol));
        }

        if ($exchange = $this->option('exchange')) {
            $query->whereHas('asset', fn ($q) => $q->where('exchange', $exchange));
        }

        if ($timeframe = $this->option('timeframe')) {
            $query->where('timeframe', $timeframe);
        }

        $watches = $query->where('is_active', true)->get();

        if ($watches->isEmpty()) {
            $this->warn('No active watches to sync.');
            return self::SUCCESS;
        }

        foreach ($watches as $watch) {
            CheckAssetWatch::dispatch($watch);
        }

        $this->info(sprintf('Queued %d watch checks.', $watches->count()));

        return self::SUCCESS;
    }
}
