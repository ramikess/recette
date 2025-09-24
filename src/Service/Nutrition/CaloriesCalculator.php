<?php

declare(strict_types=1);

namespace App\Service\Nutrition;

use App\Entity\Ingredient;
use App\Enum\IngredientUnit;
use App\Utils\StringUtils;
use Doctrine\Common\Collections\Collection;

class CaloriesCalculator
{
    private const int KCAL_ATTR_ID = 208;

    /**
     * @param Collection<int, Ingredient> $internalIngredients
     * @param array<array<string, mixed>> $externalIngredients
     */
    public function hydrateIngredientsCalorie(Collection $internalIngredients, array $externalIngredients): void
    {
        /** @var Ingredient $internalIngredient */
        foreach ($internalIngredients as $internalIngredient) {
            $calorie = $this->getIngredientCalorie($internalIngredient, $externalIngredients);
            $internalIngredient->setCalories($calorie);
        }
    }

    /**
     * @param array<array<string, mixed>> $externalIngredients
     */
    private function getIngredientCalorie(Ingredient $internalIngredient, array $externalIngredients): float|int
    {
        $internalQuantity = StringUtils::lowerTrim($internalIngredient->getQuantity());
        $externalIngredient = $this->findExternalIngredient($externalIngredients, StringUtils::lowerTrim($internalIngredient->getName()));

        if (empty($externalIngredient)) {
            return 0;
        }

        $kcalPerGram = $this->calculateCaloriesPerGram($externalIngredient);

        // Cas spéciaux
        if (IngredientUnit::isSpecial($internalQuantity)) {
            return 0; // "to taste" -> 0 kcal
        }

        // --- Quantité numérique ---
        $parsedQuantity = StringUtils::extractTextBeforeSpace($internalQuantity, 1);
        $externalQuantity = StringUtils::normalizeQuantityValue($parsedQuantity);

        // --- Détermination de l’unité ---
        $normalizedUnit = null;
        if (!is_numeric($internalQuantity)) {
            // extrait le 2ème mot ("teaspoon", "clove", "cup", etc.)
            $externalUnit = StringUtils::extractTextBeforeSpace($internalQuantity, 2);
            $externalUnit = StringUtils::lowerTrim($externalUnit);

            $normalizedUnit = IngredientUnit::fromString($externalUnit);
        }

        // --- Si une unité valide a été trouvée, on cherche dans alt_measures ---
        if (null !== $normalizedUnit && false === IngredientUnit::isSize($normalizedUnit[0])) {
            $numberCalories = $this->getNumberCalories(
                $externalIngredient['alt_measures'],
                $kcalPerGram,
                $externalQuantity,
                $normalizedUnit
            );

            if (!is_null($numberCalories)) {
                return $numberCalories;
            }
        }

        // --- si non : Fallback ---
        $defaultGramsPerUnit = $externalIngredient['serving_weight_grams'] / $externalIngredient['serving_qty'];

        return round($externalQuantity * $kcalPerGram * $defaultGramsPerUnit, 2);
    }

    /** *
     * @param array<array<string, int|string>> $altMeasures
     */
    private function getNumberCalories(array $altMeasures, float $kcalPerGram, float $quantity, string $externalUnit): ?float
    {
        foreach ($altMeasures as $altMeasure) {
            if (IngredientUnit::isSpecial($altMeasure['measure'])) {
                continue;
            }

            $measure = $altMeasure['measure'];

            if (str_contains($measure, $externalUnit)) {
                $gramsPerUnit = $altMeasure['serving_weight'] / $altMeasure['qty'];

                return round($kcalPerGram * $gramsPerUnit * $quantity, 2);
            }

        }

        return null;
    }

    /**
     * @param array<array<string, mixed>> $externalIngredients
     *
     * @return array<string, mixed>|null
     */
    private function findExternalIngredient(array $externalIngredients, string $internalIngredientName): ?array
    {
        foreach ($externalIngredients as $externalIngredient) {
            $externalIngredientName = $externalIngredient['food_name'];
            if (StringUtils::containsEither($internalIngredientName, $externalIngredientName)) {
                return $externalIngredient;
            }
        }

        return null;
    }

    /**
     * Calcule les kilocalories par gramme pour la portion par défaut d’un aliment.
     *
     * @param array<string, mixed> $externalIngredient
     */
    private function calculateCaloriesPerGram(array $externalIngredient): float
    {
        $defaultServingGrams = $externalIngredient['serving_weight_grams'];
        $caloriesForDefaultServing = $this->getFullNutrientValue($externalIngredient);

        return $caloriesForDefaultServing / $defaultServingGrams;
    }

    /**
     * Récupère la quantité par défaut du nutriment pour la portion.
     *
     * @param array<string, mixed> $externalIngredient
     */
    private function getFullNutrientValue(array $externalIngredient): ?float
    {
        foreach ($externalIngredient['full_nutrients'] as $nutrient) {
            if (self::KCAL_ATTR_ID === $nutrient['attr_id']) {
                return $nutrient['value'];
            }
        }

        return null;
    }
}
