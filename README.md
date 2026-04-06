# Endian

[![PHP Version Requirement](https://img.shields.io/packagist/dependency-v/thesis/endian/php)](https://packagist.org/packages/thesis/endian)
[![GitHub Release](https://img.shields.io/github/v/release/thesisphp/endian)](https://github.com/thesisphp/endian/releases)
[![Code Coverage](https://codecov.io/gh/thesis-php/endian/branch/0.4.x/graph/badge.svg)](https://codecov.io/gh/thesis-php/endian/tree/0.4.x)

Pack and unpack binary integers and floats in any byte order.

## Installation

```shell
composer require thesis/endian
```

## Usage

```php
use Thesis\Endian;

// Big endian (= network byte order)
$bytes = Endian\Order::Big->packInt32(-200);
$value = Endian\Order::Big->unpackInt32($bytes); // -200
$bytes = Endian\Order::Network->packInt32(-200);
$value = Endian\Order::Network->unpackInt32($bytes); // -200

// Little endian
$bytes = Endian\Order::Little->packFloat(2.2);
$value = Endian\Order::Little->unpackFloat($bytes); // 2.2

// Native byte order of the current machine
$order = Endian\Order::native(); // Order::Big or Order::Little
```

## Design decisions

### No narrow int types on input

Pack methods accept plain `int` rather than narrow PHPStan types like `Int8` or `Int32`. This is intentional: requiring
callers to carry and assert narrow types would flood driver code with redundant checks and type imports. Thus, we've
decided to keep the validation on our side.

Bounds are checked with [`assert()`](https://www.php.net/manual/en/function.assert.php), which means zero overhead
in production when assertions are disabled ([`zend.assertions = -1`](https://www.php.net/manual/en/info.configuration.php#ini.zend.assertions)).

### No object wrappers for 8/16/32-bit

8/16/32-bit integers are represented as native PHP `int`, not value objects. This avoids allocation and method-call
overhead on every pack/unpack — important in tight loops typical of binary protocol parsing.

### 64-bit integers

64-bit values use [`BcMath\Number`](https://www.php.net/manual/en/class.bcmath-number.php)
to handle the full unsigned range beyond `PHP_INT_MAX`:

```php
use Thesis\Endian;
use BcMath\Number;

$bytes = Endian\Order::Big->packUint64(new Number('18446744073709551615'));
$value = Endian\Order::Big->unpackUint64($bytes); // 18446744073709551615
```

## Supported types

| Type     | PHP type        | Range                                                                                   |
|----------|-----------------|-----------------------------------------------------------------------------------------|
| `int8`   | `int`           | `−128 .. 127`                                                                           |
| `uint8`  | `int`           | `0 .. 255`                                                                              |
| `int16`  | `int`           | `−32 768 .. 32767`                                                                      |
| `uint16` | `int`           | `0 .. 65535`                                                                            |
| `int32`  | `int`           | `−2147483648 .. 2147483647`                                                             |
| `uint32` | `int`           | `0 .. 4294967295`                                                                       |
| `int64`  | `BcMath\Number` | `−2⁶³ .. 2⁶³−1`                                                                         |
| `uint64` | `BcMath\Number` | `0 .. 2⁶⁴−1`                                                                            |
| `float`  | `float`         | [32-bit IEEE 754](https://en.wikipedia.org/wiki/Single-precision_floating-point_format) |
| `double` | `float`         | [64-bit IEEE 754](https://en.wikipedia.org/wiki/Double-precision_floating-point_format) |
