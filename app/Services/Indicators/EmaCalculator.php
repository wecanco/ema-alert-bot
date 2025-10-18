<?php

namespace App\Services\Indicators;

use Illuminate\Support\Collection;

class EmaCalculator
{
    public function calculate(Collection $candles, int $length): ?float
    {
        if ($candles->count() < $length) {
            return null;
        }

        $prices = $candles->pluck('close')->map(fn ($value) => (float) $value);
        $k = 2 / ($length + 1);
        $ema = $prices->take($length)->average();

        foreach ($prices->slice($length) as $price) {
            $ema = ($price - $ema) * $k + $ema;
        }

        return $ema;
    }
}
