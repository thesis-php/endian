# Endian

## Installation

```shell
composer require thesis/endian
```

## Basic usage

```php
<?php

declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

use Thesis\Endian\endian;

echo endian::network->unpackInt32(
    endian::network->packInt32(-200),
); // -200
```
