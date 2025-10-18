<?php

namespace App\Providers;

use App\Models\Asset;
use App\Models\AssetWatch;
use App\Models\User;
use App\Observers\AssetObserver;
use App\Observers\AssetWatchObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Asset::observe(AssetObserver::class);
        AssetWatch::observe(AssetWatchObserver::class);
        User::observe(UserObserver::class);
    }
}
