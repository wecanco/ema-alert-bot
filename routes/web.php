<?php

use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\AssetWatchController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\IntegrationController;
use App\Http\Controllers\Admin\IntegrationExportController;
use App\Http\Controllers\Admin\StrategyConfigController;
use App\Http\Controllers\Admin\TimeframeController;
use App\Http\Controllers\Admin\TelegramController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['can:access-admin-panel', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('assets', AssetController::class);
        Route::resource('asset-watches', AssetWatchController::class);
        Route::resource('users', UserController::class);
        Route::resource('strategy-configs', StrategyConfigController::class)->only(['index', 'edit', 'update']);
        Route::resource('integrations', IntegrationController::class)->only(['index', 'update']);
        Route::patch('integrations/{integration}/toggle', [IntegrationController::class, 'toggle'])
            ->name('integrations.toggle');
        Route::get('integrations/{integration}/export', IntegrationExportController::class)
            ->name('integrations.export');
        Route::resource('timeframes', TimeframeController::class);
        Route::view('telegram', 'admin.telegram.settings')->name('telegram.settings');
        Route::post('telegram/test', [TelegramController::class, 'test'])->name('telegram.test');
    });
});

require __DIR__.'/auth.php';
