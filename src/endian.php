<?php

declare(strict_types=1);

namespace Thesis\Endian;

/**
 * @api
 */
enum endian
{
    case big;
    case little;
    public const network = self::big;
    public const native = Internal\native;

    /**
     * @return non-empty-string
     */
    public function packInt8(int $v): string
    {
        return \chr($v);
    }

    /**
     * @param non-empty-string $v
     */
    public function unpackInt8(string $v): int
    {
        $n = $this->unpackUint8($v);

        if ($n >= 0x80) {
            $n -= 0x100;
        }

        return $n;
    }

    /**
     * @param non-negative-int $v
     * @return non-empty-string
     */
    public function packUint8(int $v): string
    {
        return \chr($v);
    }

    /**
     * @param non-empty-string $v
     * @return int<0, 255>
     */
    public function unpackUint8(string $v): int
    {
        return \ord($v);
    }

    /**
     * @return non-empty-string
     */
    public function packInt16(int $v): string
    {
        if ($v < 0) {
            $v = (1 << 16) + $v;
        }

        /** @phpstan-ignore argument.type */
        return $this->packUint16($v);
    }

    /**
     * @param non-empty-string $v
     */
    public function unpackInt16(string $v): int
    {
        $n = $this->unpackUint16($v);

        if ($n >= 0x8000) {
            $n -= 0x10000;
        }

        return $n;
    }

    /**
     * @param non-negative-int $v
     * @return non-empty-string
     */
    public function packUint16(int $v): string
    {
        return match ($this) {
            self::big => \chr($v >> 8) . \chr($v),
            self::little => \chr($v) . \chr($v >> 8),
        };
    }

    /**
     * @param non-empty-string $v
     * @return non-negative-int
     */
    public function unpackUint16(string $v): int
    {
        /** @var non-negative-int */
        return match ($this) {
            self::big => \ord($v[1]) | \ord($v[0]) << 8,
            self::little => \ord($v[0]) | \ord($v[1]) << 8,
        };
    }

    /**
     * @return non-empty-string
     */
    public function packInt32(int $v): string
    {
        if ($v < 0) {
            $v = (1 << 32) + $v;
        }

        /** @phpstan-ignore argument.type */
        return $this->packUint32($v);
    }

    /**
     * @param non-empty-string $v
     */
    public function unpackInt32(string $v): int
    {
        $n = $this->unpackUint32($v);

        if ($n >= 0x80000000) {
            $n -= 0x100000000;
        }

        return $n;
    }

    /**
     * @param non-negative-int $v
     * @return non-empty-string
     */
    public function packUint32(int $v): string
    {
        return match ($this) {
            self::big => \chr($v >> 24)
                . \chr($v >> 16)
                . \chr($v >> 8)
                . \chr($v),
            self::little => \chr($v)
                . \chr($v >> 8)
                . \chr($v >> 16)
                . \chr($v >> 24),
        };
    }

    /**
     * @param non-empty-string $v
     * @return non-negative-int
     */
    public function unpackUint32(string $v): int
    {
        /** @var non-negative-int */
        return match ($this) {
            self::big => \ord($v[3])
                | \ord($v[2]) << 8
                | \ord($v[1]) << 16
                | \ord($v[0]) << 24,
            self::little => \ord($v[0])
                | \ord($v[1]) << 8
                | \ord($v[2]) << 16
                | \ord($v[3]) << 24,
        };
    }

    /**
     * @return non-empty-string
     */
    public function packInt64(int $v): string
    {
        return $this->packUint64($v);
    }

    /**
     * @param non-empty-string $v
     */
    public function unpackInt64(string $v): int
    {
        return $this->unpackUint64($v);
    }

    /**
     * @return non-empty-string
     */
    public function packUint64(int $v): string
    {
        return match ($this) {
            self::big => \chr($v >> 56)
                . \chr($v >> 48)
                . \chr($v >> 40)
                . \chr($v >> 32)
                . \chr($v >> 24)
                . \chr($v >> 16)
                . \chr($v >> 8)
                . \chr($v),
            self::little => \chr($v)
                . \chr($v >> 8)
                . \chr($v >> 16)
                . \chr($v >> 24)
                . \chr($v >> 32)
                . \chr($v >> 40)
                . \chr($v >> 48)
                . \chr($v >> 56),
        };
    }

    /**
     * @param non-empty-string $v
     * @return non-negative-int
     */
    public function unpackUint64(string $v): int
    {
        return match ($this) {
            self::big => \ord($v[7])
                | \ord($v[6]) << 8
                | \ord($v[5]) << 16
                | \ord($v[4]) << 24
                | \ord($v[3]) << 32
                | \ord($v[2]) << 40
                | \ord($v[1]) << 48
                | \ord($v[0]) << 56,
            self::little => \ord($v[0])
                | \ord($v[1]) << 8
                | \ord($v[2]) << 16
                | \ord($v[3]) << 24
                | \ord($v[4]) << 32
                | \ord($v[5]) << 40
                | \ord($v[6]) << 48
                | \ord($v[7]) << 56,
        };
    }

    /**
     * @return non-empty-string
     */
    public function packFloat(float $v): string
    {
        return Internal\packBytes($v, match ($this) {
            self::big => 'G',
            self::little => 'g',
        });
    }

    /**
     * @param non-empty-string $v
     */
    public function unpackFloat(string $v): float
    {
        return (float) Internal\unpackBytes($v, match ($this) {
            self::big => 'G',
            self::little => 'g',
        });
    }

    /**
     * @return non-empty-string
     */
    public function packDouble(float $v): string
    {
        return Internal\packBytes($v, match ($this) {
            self::big => 'E',
            self::little => 'e',
        });
    }

    /**
     * @param non-empty-string $v
     */
    public function unpackDouble(string $v): float
    {
        return (float) Internal\unpackBytes($v, match ($this) {
            self::big => 'E',
            self::little => 'e',
        });
    }
}
