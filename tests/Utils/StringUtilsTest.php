<?php

declare(strict_types=1);

namespace App\Tests\Utils;

use App\Utils\StringUtils;
use PHPUnit\Framework\TestCase;

final class StringUtilsTest extends TestCase
{
    public function testLowerTrim(): void
    {
        $this->assertSame('world', StringUtils::lowerTrim('WORLD'));
        $this->assertSame('', StringUtils::lowerTrim(','));
    }

    public function testNormalizeQuantityValue(): void
    {
        // fractions
        $this->assertSame(0.75, StringUtils::normalizeQuantityValue('3/4'));
        $this->assertSame(0.5, StringUtils::normalizeQuantityValue('1/2'));

        // ranges
        $this->assertSame(7.0, StringUtils::normalizeQuantityValue('6-8'));
        $this->assertSame(3.5, StringUtils::normalizeQuantityValue('3-4'));

        // simple numbers
        $this->assertSame(2.0, StringUtils::normalizeQuantityValue('2'));
        $this->assertSame(2.5, StringUtils::normalizeQuantityValue('2,5'));

        // invalid
        $this->assertNull(StringUtils::normalizeQuantityValue('abc'));
        $this->assertNull(StringUtils::normalizeQuantityValue(''));
    }

    public function testContainsEither(): void
    {
        $this->assertTrue(StringUtils::containsEither('Hello World', 'world'));
        $this->assertTrue(StringUtils::containsEither('world', 'Hello World'));
        $this->assertFalse(StringUtils::containsEither('Hello', ''));
        $this->assertFalse(StringUtils::containsEither('', 'World'));
        $this->assertFalse(StringUtils::containsEither('foo', 'bar'));
    }

    public function testExtractTextBeforeSpace(): void
    {
        $this->assertSame('Hello', StringUtils::extractTextBeforeSpace('Hello World'));
        $this->assertSame('World', StringUtils::extractTextBeforeSpace('Hello World', 2));
        $this->assertNull(StringUtils::extractTextBeforeSpace('SingleWord', 2));
    }
}