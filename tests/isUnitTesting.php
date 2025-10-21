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

$filterValue = getCliOptionValue($arguments, '--testsuite');

$isUnitTests = true;

if (strcasecmp($filterValue, 'integration') === 0) {
    $isUnitTests = false;
}

return $isUnitTests;