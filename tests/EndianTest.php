<?php

declare(strict_types=1);

namespace Thesis\Endian;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(endian::class)]
final class EndianTest extends TestCase
{
    public function testInt8(): void
    {
        foreach ([endian::big, endian::little] as $endian) {
            foreach ($this->sequence([-128, 127]) as $i) {
                self::assertSame($i, $endian->unpackInt8($endian->packInt8($i)));
            }
        }
    }

    public function testUint8(): void
    {
        foreach ([endian::big, endian::little] as $endian) {
            foreach ($this->sequence([0, 255]) as $i) {
                self::assertSame($i, $endian->unpackUint8($endian->packUint8($i)));
            }
        }
    }

    public function testInt16(): void
    {
        foreach ([endian::big, endian::little] as $endian) {
            foreach ($this->sequence([-32768, 32767]) as $i) {
                self::assertSame($i, $endian->unpackInt16($endian->packInt16($i)));
            }
        }
    }

    public function testUint16(): void
    {
        foreach ([endian::big, endian::little] as $endian) {
            foreach ($this->sequence([0, 65535]) as $i) {
                self::assertSame($i, $endian->unpackUint16($endian->packUint16($i)));
            }
        }
    }

    public function testInt32(): void
    {
        foreach ([endian::big, endian::little] as $endian) {
            foreach ($this->sequence([-32768, 32767], [-2147483648], [2147483647]) as $i) {
                self::assertSame($i, $endian->unpackInt32($endian->packInt32($i)));
            }
        }
    }

    public function testUint32(): void
    {
        foreach ([endian::big, endian::little] as $endian) {
            foreach ($this->sequence([0, 65535], [4294967295]) as $i) {
                self::assertSame($i, $endian->unpackUint32($endian->packUint32($i)));
            }
        }
    }

    public function testInt64(): void
    {
        foreach ([endian::big, endian::little] as $endian) {
            foreach ($this->sequence([-32768, 32767], [PHP_INT_MIN], [PHP_INT_MAX]) as $i) {
                self::assertSame($i, $endian->unpackInt64($endian->packInt64($i)));
            }
        }
    }

    public function testUint64(): void
    {
        foreach ([endian::big, endian::little] as $endian) {
            foreach ($this->sequence([0, 65535], [PHP_INT_MAX]) as $i) {
                self::assertSame($i, $endian->unpackUint64($endian->packUint64($i)));
            }
        }
    }

    public function testFloat(): void
    {
        foreach ([endian::big, endian::little] as $endian) {
            foreach ($this->sequence([-32768, 32767]) as $i) {
                self::assertSame($i * 1.0, $endian->unpackFloat($endian->packFloat($i)));
            }
        }
    }

    public function testDouble(): void
    {
        foreach ([endian::big, endian::little] as $endian) {
            foreach ($this->sequence([-32768, 32767], [2.2250738585072E-308], [1.7976931348623E+308], [PHP_INT_MIN], [PHP_INT_MAX]) as $i) {
                self::assertSame($i * 1.0, $endian->unpackDouble($endian->packDouble($i)));
            }
        }
    }

    public function testNetworkIsBigEndian(): void
    {
        self::assertSame(endian::big, endian::network);
    }

    /**
     * @template T of int|float
     * @param array{0: T, 1?: T} ...$ranges
     * @return \Generator<T>
     */
    private function sequence(array ...$ranges): \Generator
    {
        foreach ($ranges as $range) {
            /** @var T $value */
            foreach (range($range[0], $range[1] ?? $range[0]) as $value) {
                yield $value;
            }
        }
    }
}
