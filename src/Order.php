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
    case Big;
    case Little;
    public const self Network = self::Big;

    #[\Deprecated('Use `Thesis\Endian\Order::Big` instead. Will be removed in 0.4.0.', '0.3.1')]
    public const self big = self::Big;

    #[\Deprecated('Use `Thesis\Endian\Order::Little` instead. Will be removed in 0.4.0.', '0.3.1')]
    public const self little = self::Little;

    #[\Deprecated('Use `Thesis\Endian\Order::Network` instead. Will be removed in 0.4.0.', '0.3.1')]
    public const self network = self::Network;

    public static function native(): self
    {
        /** @var ?self $order */
        static $order;
        $order ??= self::isLittleEndianMachine() ? Order::Little : Order::Big;

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
        return self::packBytes($num, match ($this) {
            self::Big => 'n',
            self::Little => 'v',
        });
    }

    /**
     * @param non-empty-string $v
     * @return Uint16
     */
    public function unpackUint16(string $v): int
    {
        /** @var Uint16 */
        return self::unpackBytes($v, match ($this) {
            self::Big => 'n',
            self::Little => 'v',
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
        return self::packBytes($num, match ($this) {
            self::Big => 'N',
            self::Little => 'V',
        });
    }

    /**
     * @param non-empty-string $v
     * @return Uint32
     */
    public function unpackUint32(string $v): int
    {
        /** @var Uint32 */
        return self::unpackBytes($v, match ($this) {
            self::Big => 'N',
            self::Little => 'V',
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
            /** @var int<0, 255> $value */
            $value = (int) $num->mod(256)->value;
            $bytes .= \chr($value);
            $num = $num->div(256, scale: 0);
        }

        /** @var non-empty-string */
        return match ($this) {
            self::Big => strrev($bytes),
            self::Little => $bytes,
        };
    }

    /**
     * @param non-empty-string $v
     */
    public function unpackUint64(string $v): Number
    {
        return match ($this) {
            self::Big => self::unpackUint64BE($v),
            self::Little => self::unpackUint64LE($v),
        };
    }

    /**
     * @return non-empty-string
     */
    public function packFloat(float $num): string
    {
        return self::packBytes($num, match ($this) {
            self::Big => 'G',
            self::Little => 'g',
        });
    }

    /**
     * @param non-empty-string $v
     */
    public function unpackFloat(string $v): float
    {
        return (float) self::unpackBytes($v, match ($this) {
            self::Big => 'G',
            self::Little => 'g',
        });
    }

    /**
     * @return non-empty-string
     */
    public function packDouble(float $num): string
    {
        return self::packBytes($num, match ($this) {
            self::Big => 'E',
            self::Little => 'e',
        });
    }

    /**
     * @param non-empty-string $v
     */
    public function unpackDouble(string $v): float
    {
        return (float) self::unpackBytes($v, match ($this) {
            self::Big => 'E',
            self::Little => 'e',
        });
    }

    /**
     * @internal
     * @param non-empty-string $v
     */
    private static function unpackUint64BE(string $v): Number
    {
        $num = new Number(0);

        for ($i = 0; $i < 8; ++$i) {
            $num += new Number(\ord($v[$i])) * new Number(256)->pow(7 - $i, scale: 0);
        }

        return $num;
    }

    /**
     * @param non-empty-string $v
     */
    private static function unpackUint64LE(string $v): Number
    {
        $num = new Number(0);

        for ($i = 0; $i < 8; ++$i) {
            $num += new Number(\ord($v[$i])) * new Number(256)->pow($i, scale: 0);
        }

        return $num;
    }

    /**
     * @param non-empty-string $bytes
     * @param non-empty-string $format
     */
    private static function unpackBytes(string $bytes, string $format): string|int|float
    {
        /** @var string|int|float */
        return unpack($format, $bytes)[1] ?? throw new \RuntimeException(\sprintf('Cannot unpack "%s" using "%s".', $bytes, $format));
    }

    /**
     * @param non-empty-string $format
     * @return non-empty-string
     */
    private static function packBytes(mixed $value, string $format): string
    {
        /** @var non-empty-string */
        return pack($format, $value);
    }

    private static function isLittleEndianMachine(): bool
    {
        return (int) self::unpackBytes("\x01\x00", 'S') === 1;
    }
}
