<?php

declare(strict_types=1);

namespace Thesis\Endian;

use BcMath\Number;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Order::class)]
final class OrderTest extends TestCase
{
    public function testInt8(): void
    {
        foreach ([Order::Big, Order::Little] as $endian) {
            foreach ($this->sequence([-128, 127]) as $i) {
                self::assertSame($i, $endian->unpackInt8($endian->packInt8($i)));
            }
        }
    }

    public function testUint8(): void
    {
        foreach ([Order::Big, Order::Little] as $endian) {
            foreach ($this->sequence([0, 255]) as $i) {
                self::assertSame($i, $endian->unpackUint8($endian->packUint8($i)));
            }
        }
    }

    public function testInt16(): void
    {
        foreach ([Order::Big, Order::Little] as $endian) {
            foreach ($this->sequence([-32_768, 32_767]) as $i) {
                self::assertSame($i, $endian->unpackInt16($endian->packInt16($i)));
            }
        }
    }

    public function testUint16(): void
    {
        foreach ([Order::Big, Order::Little] as $endian) {
            foreach ($this->sequence([0, 65_535]) as $i) {
                self::assertSame($i, $endian->unpackUint16($endian->packUint16($i)));
            }
        }
    }

    public function testInt32(): void
    {
        foreach ([Order::Big, Order::Little] as $endian) {
            foreach ($this->sequence([-32_768, 32_767], [-2_147_483_648], [2_147_483_647]) as $i) {
                self::assertSame($i, $endian->unpackInt32($endian->packInt32($i)));
            }
        }
    }

    public function testUint32(): void
    {
        foreach ([Order::Big, Order::Little] as $endian) {
            foreach ($this->sequence([0, 65_535], [4_294_967_295]) as $i) {
                self::assertSame($i, $endian->unpackUint32($endian->packUint32($i)));
            }
        }
    }

    public function testInt64(): void
    {
        foreach ([Order::Big, Order::Little] as $endian) {
            foreach ($this->sequence([-32_768, 32_767], [PHP_INT_MIN], [PHP_INT_MAX]) as $i) {
                $num = new Number($i);

                self::assertEquals($num, $endian->unpackInt64($endian->packInt64($num)));
            }

            foreach ([new Number('9223372036854775807'), new Number('-9223372036854775808')] as $num) {
                self::assertEquals($num, $endian->unpackInt64($endian->packInt64($num)));
            }
        }
    }

    public function testUint64(): void
    {
        foreach ([Order::Big, Order::Little] as $endian) {
            foreach ($this->sequence([0, 65_535], [PHP_INT_MAX]) as $i) {
                $num = new Number($i);

                self::assertEquals($num, $endian->unpackUint64($endian->packUint64($num)));
            }

            foreach ([new Number('18446744073709551615')] as $num) {
                self::assertEquals($num, $endian->unpackUint64($endian->packUint64($num)));
            }
        }
    }

    public function testFloat(): void
    {
        foreach ([Order::Big, Order::Little] as $endian) {
            foreach ($this->sequence([-32_768, 32_767]) as $i) {
                self::assertSame($i * 1.0, $endian->unpackFloat($endian->packFloat($i)));
            }
        }
    }

    public function testDouble(): void
    {
        foreach ([Order::Big, Order::Little] as $endian) {
            foreach ($this->sequence([-32_768, 32_767], [2.225_073_858_507_2E-308], [1.797_693_134_862_3], [PHP_INT_MIN], [PHP_INT_MAX]) as $i) {
                self::assertSame($i * 1.0, $endian->unpackDouble($endian->packDouble($i)));
            }
        }
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
