# Endian

[![PHP Version Requirement](https://img.shields.io/packagist/dependency-v/thesis/endian/php)](https://packagist.org/packages/thesis/endian)
[![GitHub Release](https://img.shields.io/github/v/release/thesisphp/endian)](https://github.com/thesisphp/endian/releases)
[![Code Coverage](https://codecov.io/gh/thesis-php/endian/branch/0.1.x/graph/badge.svg)](https://codecov.io/gh/thesis-php/endian/tree/0.1.x)

## Installation

```shell
composer require thesis/endian
```

## Read/write in any byte order:

1. In `network` (`big endian`) byte order.

```php
use Thesis\Endian;

echo Endian\Order::Network->unpackInt32(
    Endian\Order::Network->packInt32(-200),
); // -200
```

2. In `big endian` byte order.

```php
use Thesis\Endian;

echo Endian\Order::Big->unpackInt8(
    Endian\Order::Big->packInt8(17),
); // 17
```

3. In `little endian` byte order.

```php
use Thesis\Endian;

echo Endian\Order::Little->unpackFloat(
    Endian\Order::Little->packFloat(2.2),
); // 2.2
```

4. In `native endian` byte order.

```php
use Thesis\Endian;
use BcMath\Number;

echo Endian\Order::native()
    ->unpackInt64(
        Endian\Order::native()->packInt64(new Number('9223372036854775807')),
    )
    ->value; // 9223372036854775807
```

### Supported types:
- [x] `int8`
- [x] `uint8`
- [x] `int16`
- [x] `uint16`
- [x] `int32`
- [x] `uint32`
- [x] `int64`
- [x] `uint64`
- [x] `float`
- [x] `double`
