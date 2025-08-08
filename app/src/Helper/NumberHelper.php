<?php

namespace App\Helper;

class NumberHelper {

    public function roundToNiceNumber(float $value): int
    {
        if ($value <= 0) {
            return 0;
        }

        // Trouve la puissance de 10 la plus proche
        $exponent = floor(log10($value));
        $base = 10 ** $exponent;

        // Choix de jolis multiples
        $multipliers = [1, 2, 2.5, 5, 7.5, 10];

        foreach ($multipliers as $m) {
            if ($value <= $m * $base) {
                return (int)($m * $base);
            }
        }

        // Sinon on arrondit au multiple de 10 supérieur
        return (int)(10 * $base);
    }
}