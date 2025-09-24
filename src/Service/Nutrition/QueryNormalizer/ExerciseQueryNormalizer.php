<?php

declare(strict_types=1);

namespace App\Service\Nutrition\QueryNormalizer;

use App\Enum\NutritionAnalysisType;

class ExerciseQueryNormalizer implements NutritionQueryNormalizerInterface
{
    public function supports(NutritionAnalysisType $nutritionAnalysisType): bool
    {
        return NutritionAnalysisType::Exercise === $nutritionAnalysisType;
    }

    public function normalize(object $subject): array
    {
        throw new \LogicException('Exercise normalizer not yet implemented.');
    }
}
