<?php

declare(strict_types=1);

namespace App\Utils;

final readonly class StringUtils
{
    public static function lowerTrim(string $value): string
    {
        return strtolower(trim($value, ','));
    }

    /**
     * Extrait une valeur numérique depuis un texte de quantité.
     * Exemples : "3/4" → 0.75 ; "6-8" → 7.0 ; "2" → 2.0 ; sinon null.
     */
    public static function normalizeQuantityValue(string $text): ?float
    {
        // "a/b"
        if (preg_match('#^(\d+)\s*/\s*(\d+)$#', $text, $matches)) {
            return $matches[1] / $matches[2];
        }

        // "a-b" = (a+b)/2 une plage en un nombre moyen :
        if (preg_match('#^(\d+)\s*-\s*(\d+)$#', $text, $matches)) {
            return (float) (($matches[1] + $matches[2]) / 2);
        }

        $text = str_replace(',', '.', $text);

        return is_numeric($text) ? (float) $text : null;
    }

    public static function containsEither(string $text1, string $text2): bool
    {
        $text1 = strtolower(trim($text1));
        $text2 = strtolower(trim($text2));

        if ('' === $text1 || '' === $text2) {
            return false;
        }

        return str_contains($text1, $text2) || str_contains($text2, $text1);
    }

    public static function extractTextBeforeSpace(string $internalQuantity, int $spacePosition = 1): ?string
    {
        $text = explode(' ', $internalQuantity);

        return $text[$spacePosition - 1] ?? null;
    }
}
