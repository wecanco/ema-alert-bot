<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\Integration;
use App\Services\MarketData\MarketDataManager;
use App\Services\MarketData\Contracts\MarketDataClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MarketDataManager::class, fn () => new MarketDataManager());
        $this->app->bind(MarketDataClient::class, function ($app) {
            $manager = $app->make(MarketDataManager::class);
            $provider = config('marketdata.default');

            return $manager->client($provider);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::aliasMiddleware('admin', \App\Http\Middleware\EnsureAdmin::class);

        try {
            if (Schema::hasTable('integrations')) {
                $token = Cache::remember('telegram.bot_token', 300, function () {
                    return Integration::where('type', 'telegram')
                        ->where('is_active', true)
                        ->value('config->bot_token');
                });

                if ($token) {
                    config(['services.telegram.bot_token' => $token]);
                }
            }
        } catch (\Throwable $e) {
            // quietly ignore during migrations
        }
    }
}
