<?php

declare(strict_types=1);

namespace App\Enum;

enum IngredientUnit: string
{
    case Cup = 'cup';
    case Teaspoon = 'teaspoon';
    case Tablespoon = 'tablespoon';

    case Ounce = 'oz';
    case Pound = 'lb';

    case Clove = 'clove';
    case Head = 'head';
    case Can = 'can';

    // unités spéciales
    case ToTaste = 'to taste';
    case ForGarnish = 'for garnish';

    case Large = 'large';
    case Medium = 'medium';
    case Small = 'small';

    /** @var array<string, list<string>> */
    private const array ALIASES = [
        self::Cup->value => ['cup', 'cups'],
        self::Teaspoon->value => ['teaspoon', 'teaspoons'],
        self::Tablespoon->value => ['tablespoon', 'tablespoons'],

        self::Ounce->value => ['oz'],
        self::Pound->value => ['lb', 'lbs'],

        self::Clove->value => ['clove', 'cloves'],
        self::Head->value => ['head', 'heads'],
        self::Can->value => ['can', 'cans'],

        self::Large->value => ['large'],
        self::Medium->value => ['medium'],
        self::Small->value => ['small'],
    ];

    public static function fromString(string $value): ?string
    {
        foreach (self::ALIASES as $case => $aliases) {
            if (in_array($value, $aliases, true)) {
                return $case;
            }
        }

        return null;
    }

    public static function isSpecial(string $quantity): bool
    {
        return self::containsAny($quantity, [
            self::ToTaste,
            self::ForGarnish,
        ]);
    }

    public static function isSize(string $quantity): bool
    {
        return self::containsAny($quantity, [
            self::Large,
            self::Medium,
            self::Small,
        ]);
    }

    /**
     * @param array<IngredientUnit> $units
     */
    private static function containsAny(string $quantity, array $units): bool
    {
        return array_any($units, fn ($unit) => str_contains($quantity, $unit->value));
    }
}
