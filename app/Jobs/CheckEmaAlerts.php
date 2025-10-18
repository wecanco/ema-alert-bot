<?php

namespace App\Jobs;

use App\Jobs\CheckAssetWatch;
use App\Models\AssetWatch;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

class CheckEmaAlerts implements ShouldQueue
{
    use Dispatchable, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $watches = AssetWatch::with('asset')
            ->where('is_active', true)
            ->get();

        $jobs = $watches->map(fn ($watch) => new CheckAssetWatch($watch))->all();

        if (empty($jobs)) {
            return;
        }

        Bus::batch(Arr::wrap($jobs))
            ->allowFailures()
            ->name('check-ema-alerts')
            ->catch(function (Batch $batch, Throwable $e) {
                Log::error('EMA alert batch failed', ['error' => $e->getMessage()]);
            })->dispatch();
    }
}
