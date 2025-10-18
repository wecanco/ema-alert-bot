<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_candles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->string('timeframe');
            $table->timestamp('opens_at');
            $table->decimal('open', 18, 8);
            $table->decimal('high', 18, 8);
            $table->decimal('low', 18, 8);
            $table->decimal('close', 18, 8);
            $table->decimal('volume', 24, 8)->nullable();
            $table->timestamps();
            $table->unique(['asset_id', 'timeframe', 'opens_at']);
            $table->index(['timeframe', 'opens_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_candles');
    }
};
