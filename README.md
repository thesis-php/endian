# Typhoon Endian

## Installation

```shell
composer require typhoon/endian
```

## Basic usage

```php
<?php

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

use Typhoon\Endian\endian;

echo endian::network->unpackInt32(
    endian::network->packInt32(-200),
); // -200
```