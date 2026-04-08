<?php

declare(strict_types=1);

namespace Thesis\Endian\Internal;

use BcMath\Number;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

#[CoversClass(Ints::class)]
final class IntsTest extends TestCase
{
    #[TestWith([Ints::INT8_MIN, true])]
    #[TestWith([Ints::INT8_MAX, true])]
    #[TestWith([Ints::INT8_MIN - 1, false])]
    #[TestWith([Ints::INT8_MAX + 1, false])]
    public function testIsInt8(int $num, bool $expected): void
    {
        self::assertSame($expected, Ints::isInt8($num));
    }

    #[TestWith([0, true])]
    #[TestWith([Ints::UINT8_MAX, true])]
    #[TestWith([-1, false])]
    #[TestWith([Ints::UINT8_MAX + 1, false])]
    public function testIsUint8(int $num, bool $expected): void
    {
        self::assertSame($expected, Ints::isUint8($num));
    }

    #[TestWith([Ints::INT16_MIN, true])]
    #[TestWith([Ints::INT16_MAX, true])]
    #[TestWith([Ints::INT16_MIN - 1, false])]
    #[TestWith([Ints::INT16_MAX + 1, false])]
    public function testIsInt16(int $num, bool $expected): void
    {
        self::assertSame($expected, Ints::isInt16($num));
    }

    #[TestWith([0, true])]
    #[TestWith([Ints::UINT16_MAX, true])]
    #[TestWith([-1, false])]
    #[TestWith([Ints::UINT16_MAX + 1, false])]
    public function testIsUint16(int $num, bool $expected): void
    {
        self::assertSame($expected, Ints::isUint16($num));
    }

    #[TestWith([Ints::INT32_MIN, true])]
    #[TestWith([Ints::INT32_MAX, true])]
    #[TestWith([Ints::INT32_MIN - 1, false])]
    #[TestWith([Ints::INT32_MAX + 1, false])]
    public function testIsInt32(int $num, bool $expected): void
    {
        self::assertSame($expected, Ints::isInt32($num));
    }

    #[TestWith([0, true])]
    #[TestWith([Ints::UINT32_MAX, true])]
    #[TestWith([-1, false])]
    #[TestWith([Ints::UINT32_MAX + 1, false])]
    public function testIsUint32(int $num, bool $expected): void
    {
        self::assertSame($expected, Ints::isUint32($num));
    }

    #[TestWith([Ints::INT64_MIN, true])]
    #[TestWith([Ints::INT64_MAX, true])]
    /** @phpstan-ignore binaryOp.invalid */
    #[TestWith([Ints::INT64_MIN - 1, false])]
    #[TestWith([Ints::INT64_MAX + 1, false])]
    public function testIsInt64(Number $num, bool $expected): void
    {
        self::assertSame($expected, Ints::isInt64($num));
    }

    #[TestWith([new Number(0), true])]
    #[TestWith([Ints::UINT64_MAX, true])]
    #[TestWith([new Number(-1), false])]
    #[TestWith([Ints::UINT64_MAX + 1, false])]
    public function testIsUint64(Number $num, bool $expected): void
    {
        self::assertSame($expected, Ints::isUint64($num));
    }
}
