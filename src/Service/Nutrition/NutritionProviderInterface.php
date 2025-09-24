<?php

declare(strict_types=1);

namespace App\Service\Nutrition;

use App\Enum\NutritionAnalysisType;

interface NutritionProviderInterface
{
    /**
     * Analyse nutritionnelle.
     *
     * @param array<string> $query
     *
     * @return array<array{
     *      food_name: string,
     *      brand_name: string|null,
     *      serving_qty: int|float,
     *      serving_unit: string,
     *      serving_weight_grams: float,
     *      nf_calories: float,
     *      nf_total_fat: float,
     *      nf_saturated_fat: float,
     *      nf_cholesterol: float,
     *      nf_sodium: float,
     *      nf_total_carbohydrate: float,
     *      nf_dietary_fiber: float|int,
     *      nf_sugars: float,
     *      nf_protein: float,
     *      nf_potassium: float,
     *      nf_p: float,
     *      full_nutrients: list<array<string, mixed>>,
     *      nix_brand_name: string|null,
     *      nix_brand_id: string|null,
     *      nix_item_name: string|null,
     *      nix_item_id: string|null,
     *      upc: string|null,
     *      consumed_at: string,
     *      metadata: array<string, mixed>,
     *      source: int,
     *      ndb_no: int,
     *      tags: array<string, mixed>,
     *      alt_measures: list<array<string, mixed>>,
     *      lat: float|null,
     *      lng: float|null,
     *      meal_type: int,
     *      photo: array<string, mixed>,
     *      sub_recipe: mixed,
     *      class_code: mixed,
     *      brick_code: mixed,
     *      tag_id: mixed
     *  }>
     */
    public function analyze(array $query, NutritionAnalysisType $nutritionAnalysisType): array;
}
