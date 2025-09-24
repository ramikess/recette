<?php

declare(strict_types=1);

namespace App\Enum;

enum NutritionAnalysisType
{
    case Recipe;   // Natural Language for Nutrients
    case Exercise; // Natural Language for Exercise

    public function endpoint(): string
    {
        return match ($this) {
            self::Recipe => '/natural/nutrients',
            self::Exercise => '/natural/exercise',
        };
    }
}
