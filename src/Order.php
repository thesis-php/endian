<?php

declare(strict_types=1);

namespace Thesis\Endian;

use BcMath\Number;

/**
 * @api
 * @phpstan-type Int8 = int<-128, 127>
 * @phpstan-type Uint8 = int<0, 255>
 * @phpstan-type Int16 = int<-32768, 32767>
 * @phpstan-type Uint16 = int<0, 65535>
 * @phpstan-type Int32 = int<-2147483648, 2147483647>
 * @phpstan-type Uint32 = int<0, 4294967295>
 * @phpstan-type Int64 = int<-9223372036854775808, 9223372036854775807>
 * @phpstan-type Uint64 = int<0, 18446744073709551615>
 */
enum Order
{
    case big;
    case little;
    public const self network = self::big;

    public static function native(): self
    {
        /** @var ?self $order */
        static $order;
        $order ??= isLittleEndianMachine() ? Order::little : Order::big;

        return $order;
    }

    /**
     * @param Int8 $num
     * @return non-empty-string
     */
    public function packInt8(int $num): string
    {
        if ($num < 0) {
            $num += 256;
        }

        return $this->packUint8($num);
    }

    /**
     * @param non-empty-string $v
     * @return Int8
     */
    public function unpackInt8(string $v): int
    {
        $num = $this->unpackUint8($v);
        if ($num >= 128) {
            $num -= 256;
        }

        return $num;
    }

    /**
     * @param Uint8 $num
     * @return non-empty-string
     */
    public function packUint8(int $num): string
    {
        return \chr($num);
    }

    /**
     * @param non-empty-string $v
     * @return Uint8
     */
    public function unpackUint8(string $v): int
    {
        return \ord($v);
    }

    /**
     * @param Int16 $num
     * @return non-empty-string
     */
    public function packInt16(int $num): string
    {
        if ($num < 0) {
            $num += 65536;
        }

        return $this->packUint16($num);
    }

    /**
     * @param non-empty-string $v
     * @return Int16
     */
    public function unpackInt16(string $v): int
    {
        $num = $this->unpackUint16($v);
        if ($num >= 32768) {
            $num -= 65536;
        }

        return $num;
    }

    /**
     * @param Uint16 $num
     * @return non-empty-string
     */
    public function packUint16(int $num): string
    {
        return packBytes($num, match ($this) {
            self::big => 'n',
            self::little => 'v',
        });
    }

    /**
     * @param non-empty-string $v
     * @return Uint16
     */
    public function unpackUint16(string $v): int
    {
        /** @var Uint16 */
        return unpackBytes($v, match ($this) {
            self::big => 'n',
            self::little => 'v',
        });
    }

    /**
     * @param Int32 $num
     * @return non-empty-string
     */
    public function packInt32(int $num): string
    {
        if ($num < 0) {
            $num += 4294967296;
        }

        return $this->packUint32($num);
    }

    /**
     * @param non-empty-string $v
     * @return Int32
     */
    public function unpackInt32(string $v): int
    {
        $num = $this->unpackUint32($v);
        if ($num >= 2147483648) {
            $num -= 4294967296;
        }

        return $num;
    }

    /**
     * @param Uint32 $num
     * @return non-empty-string
     */
    public function packUint32(int $num): string
    {
        return packBytes($num, match ($this) {
            self::big => 'N',
            self::little => 'V',
        });
    }

    /**
     * @param non-empty-string $v
     * @return Uint32
     */
    public function unpackUint32(string $v): int
    {
        /** @var Uint32 */
        return unpackBytes($v, match ($this) {
            self::big => 'N',
            self::little => 'V',
        });
    }

    /**
     * @return non-empty-string
     */
    public function packInt64(Number $num): string
    {
        if ($num->compare(0) < 0) {
            $num += new Number(2)->pow(64);
        }

        return $this->packUint64($num);
    }

    /**
     * @param non-empty-string $v
     */
    public function unpackInt64(string $v): Number
    {
        $num = $this->unpackUint64($v);
        if ($num->compare(new Number(2)->pow(63)) >= 0) {
            $num = $num->sub(new Number(2)->pow(64), scale: 0);
        }

        return $num;
    }

    /**
     * @return non-empty-string
     */
    public function packUint64(Number $num): string
    {
        $bytes = '';

        for ($i = 0; $i < 8; ++$i) {
            $bytes .= \chr((int) $num->mod(256)->value);
            $num = $num->div(256, scale: 0);
        }

        /** @var non-empty-string */
        return match ($this) {
            self::big => strrev($bytes),
            self::little => $bytes,
        };
    }

    /**
     * @param non-empty-string $v
     */
    public function unpackUint64(string $v): Number
    {
        return match ($this) {
            self::big => unpackUint64BE($v),
            self::little => unpackUint64LE($v),
        };
    }

    /**
     * @return non-empty-string
     */
    public function packFloat(float $num): string
    {
        return packBytes($num, match ($this) {
            self::big => 'G',
            self::little => 'g',
        });
    }

    /**
     * @param non-empty-string $v
     */
    public function unpackFloat(string $v): float
    {
        return (float) unpackBytes($v, match ($this) {
            self::big => 'G',
            self::little => 'g',
        });
    }

    /**
     * @return non-empty-string
     */
    public function packDouble(float $num): string
    {
        return packBytes($num, match ($this) {
            self::big => 'E',
            self::little => 'e',
        });
    }

    /**
     * @param non-empty-string $v
     */
    public function unpackDouble(string $v): float
    {
        return (float) unpackBytes($v, match ($this) {
            self::big => 'E',
            self::little => 'e',
        });
    }
}

/**
 * @internal
 * @param non-empty-string $v
 */
function unpackUint64BE(string $v): Number
{
    $num = new Number(0);

    for ($i = 0; $i < 8; ++$i) {
        $num += new Number(\ord($v[$i])) * new Number(256)->pow(7 - $i, scale: 0);
    }

    return $num;
}

/**
 * @internal
 * @param non-empty-string $v
 */
function unpackUint64LE(string $v): Number
{
    $num = new Number(0);

    for ($i = 0; $i < 8; ++$i) {
        $num += new Number(\ord($v[$i])) * new Number(256)->pow($i, scale: 0);
    }

    return $num;
}

/**
 * @internal
 * @param non-empty-string $bytes
 * @param non-empty-string $format
 */
function unpackBytes(string $bytes, string $format): string|int|float
{
    /** @var string|int|float */
    return unpack($format, $bytes)[1] ?? throw new \RuntimeException(\sprintf('Cannot unpack "%s" using "%s".', $bytes, $format));
}

/**
 * @internal
 * @param non-empty-string $format
 * @return non-empty-string
 */
function packBytes(mixed $value, string $format): string
{
    /** @var non-empty-string */
    return pack($format, $value);
}

/**
 * @internal
 */
function isLittleEndianMachine(): bool
{
    return (int) unpackBytes("\x01\x00", 'S') === 1;
}
