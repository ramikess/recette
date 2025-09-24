<?php

declare(strict_types=1);

namespace App\Service\Nutrition\QueryNormalizer;

use App\Enum\NutritionAnalysisType;

interface NutritionQueryNormalizerInterface
{
    /**
     * Retourne true si ce normalizer supporte ce type d'analyse.
     */
    public function supports(NutritionAnalysisType $nutritionAnalysisType): bool;

    /**
     * Transforme l’objet métier en lignes lisibles par l’API externe.
     *
     * @param object $subject Entité à normaliser (Recipe, Exercise, …)
     *
     * @return string[]
     */
    public function normalize(object $subject): array;
}
