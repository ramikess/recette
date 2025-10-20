<?php

$arguments = $_SERVER['argv'] ?? [];

function getCliOptionValue(array $argv, string $option): ?string
{
    $value = null;
    $needleEq = $option.'=';

    foreach ($argv as $i => $arg) {
        if (str_starts_with($arg, $needleEq)) {
            $value = substr($arg, strlen($needleEq));
        } elseif ($arg === $option && isset($argv[$i + 1])) {
            $value = $argv[$i + 1];
        }
    }

    if ($value === null) {
        return null;
    }

    // Enlever d’éventuelles guillemets
    return trim($value, "\"'");
}

$filterValue = getCliOptionValue($arguments, '--filter');

$hasUnitNamespace = false;

if ($filterValue !== null) {
    // Cas 1 : exactement "Unit"
    if (strcasecmp($filterValue, 'Unit') === 0) {
        $hasUnitNamespace = true;
    }

    // Cas 2 : contient \Unit\ ou /Unit/
    if (preg_match('~[\\\\/]Unit[\\\\/]~i', $filterValue)) {
        $hasUnitNamespace = true;
    }
}

return $hasUnitNamespace;