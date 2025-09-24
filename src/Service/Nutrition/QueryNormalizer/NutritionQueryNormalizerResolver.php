<?php

declare(strict_types=1);

namespace App\Service\Nutrition\QueryNormalizer;

use App\Enum\NutritionAnalysisType;

/**
 * Service responsable de la résolution du "bon" normalizer en fonction du type d'analyse nutritionnelle.
 */
readonly class NutritionQueryNormalizerResolver
{
    /**
     * @param iterable<RecipeQueryNormalizer|ExerciseQueryNormalizer> $normalizers
     */
    public function __construct(private iterable $normalizers)
    {
    }

    /**
     * @return array<int, string>
     */
    public function normalize(object $subject, NutritionAnalysisType $type): array
    {
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->supports($type)) {
                return $normalizer->normalize($subject);
            }
        }

        throw new \RuntimeException(sprintf('No normalizer found for type %s', $type->name));
    }
}
