<?php

declare(strict_types=1);

namespace Thesis\Endian\Internal;

use BcMath\Number;

/**
 * @internal
 *
 * PHP does not allow `new` expressions in class constant initializers,
 * so this namespace-level constant is used as a workaround to define
 * BcMath\Number-based class constants (e.g. INT64_MIN, INT64_MAX).
 */
const __TWO = new Number(2);

/**
 * @internal
 *
 * @phpstan-type Int8 = int<self::INT8_MIN, self::INT8_MAX>
 * @phpstan-type Uint8 = int<0, self::UINT8_MAX>
 * @phpstan-type Int16 = int<self::INT16_MIN, self::INT16_MAX>
 * @phpstan-type Uint16 = int<0, self::UINT16_MAX>
 * @phpstan-type Int32 = int<self::INT32_MIN, self::INT32_MAX>
 * @phpstan-type Uint32 = int<0, self::UINT32_MAX>
 */
final readonly class Ints
{
    public const int INT8_MIN = -2 ** 7;
    public const int INT8_MAX = 2 ** 7 - 1;
    public const int UINT8_MAX = 2 ** 8 - 1;
    public const int UINT8_MOD = 2 ** 8;
    public const int INT16_MIN = -2 ** 15;
    public const int INT16_MAX = 2 ** 15 - 1;
    public const int UINT16_MAX = 2 ** 16 - 1;
    public const int UINT16_MOD = 2 ** 16;
    public const int INT32_MIN = -2 ** 31;
    public const int INT32_MAX = 2 ** 31 - 1;
    public const int UINT32_MAX = 2 ** 32 - 1;
    public const int UINT32_MOD = 2 ** 32;

    /** @phpstan-ignore unaryOp.invalid */
    public const Number INT64_MIN = -__TWO ** 63;
    public const Number INT64_MAX = __TWO ** 63 - 1;
    public const Number UINT64_MAX = __TWO ** 64 - 1;
    public const Number UINT64_MOD = __TWO ** 64;

    /**
     * @phpstan-assert-if-true Int8 $num
     */
    public static function isInt8(int $num): bool
    {
        return $num >= self::INT8_MIN && $num <= self::INT8_MAX;
    }

    /**
     * @phpstan-assert-if-true Uint8 $num
     */
    public static function isUint8(int $num): bool
    {
        return $num >= 0 && $num <= self::UINT8_MAX;
    }

    /**
     * @phpstan-assert-if-true Int16 $num
     */
    public static function isInt16(int $num): bool
    {
        return $num >= self::INT16_MIN && $num <= self::INT16_MAX;
    }

    /**
     * @phpstan-assert-if-true Uint16 $num
     */
    public static function isUint16(int $num): bool
    {
        return $num >= 0 && $num <= self::UINT16_MAX;
    }

    /**
     * @phpstan-assert-if-true Int32 $num
     */
    public static function isInt32(int $num): bool
    {
        return $num >= self::INT32_MIN && $num <= self::INT32_MAX;
    }

    /**
     * @phpstan-assert-if-true Uint32 $num
     */
    public static function isUint32(int $num): bool
    {
        return $num >= 0 && $num <= self::UINT32_MAX;
    }

    public static function isInt64(Number $num): bool
    {
        return $num >= self::INT64_MIN && $num <= self::INT64_MAX;
    }

    public static function isUint64(Number $num): bool
    {
        return $num >= 0 && $num <= self::UINT64_MAX;
    }

    private function __construct() {}
}
