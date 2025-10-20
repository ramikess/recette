<?php

declare(strict_types=1);

namespace App\Service\Nutrition\QueryNormalizer;

use App\Document\Ingredient;
use App\Document\Recipe;
use App\Enum\NutritionAnalysisType;

class RecipeQueryNormalizer implements NutritionQueryNormalizerInterface
{
    public function supports(NutritionAnalysisType $nutritionAnalysisType): bool
    {
        return NutritionAnalysisType::Recipe === $nutritionAnalysisType;
    }

    public function normalize(object $subject): array
    {
        if (!$subject instanceof Recipe) {
            throw new \InvalidArgumentException(sprintf('RecipeQueryNormalizer supports only instances of %s, but %s was given.', Recipe::class, get_debug_type($subject)));
        }

        $lines = [];
        foreach ($subject->getIngredients() as $ingredient) {
            $lines[] = $this->formatIngredient($ingredient);
        }

        return $lines;
    }

    private function formatIngredient(Ingredient $ingredient): string
    {
        return $ingredient->getQuantity()
            ? sprintf('%s (%s)', $ingredient->getName(), $ingredient->getQuantity())
            : $ingredient->getName();
    }
}
