<?php

namespace App\Observers;

use App\Models\AssetWatch;

class AssetWatchObserver
{
    /**
     * Handle the AssetWatch "created" event.
     */
    public function created(AssetWatch $assetWatch): void
    {
        //
    }

    /**
     * Handle the AssetWatch "updated" event.
     */
    public function updated(AssetWatch $assetWatch): void
    {
        //
    }

    /**
     * Handle the AssetWatch "deleted" event.
     */
    public function deleted(AssetWatch $assetWatch): void
    {
        //
    }

    /**
     * Handle the AssetWatch "restored" event.
     */
    public function restored(AssetWatch $assetWatch): void
    {
        //
    }

    /**
     * Handle the AssetWatch "force deleted" event.
     */
    public function forceDeleted(AssetWatch $assetWatch): void
    {
        //
    }
}
