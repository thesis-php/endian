<?php

declare(strict_types=1);

namespace Thesis\Endian\Internal;

use Thesis\Endian\endian;

if (!\defined('Thesis\Endian\Internal\native')) {
    \define('Thesis\Endian\Internal\native', namespace\isLittleEndianMachine() ? endian::little : endian::big);
}

/**
 * @internal
 */
function isLittleEndianMachine(): bool
{
    return (int) namespace\unpackBytes("\x01\x00", 'S') === 1;
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
 * @template T
 * @param T $value
 * @param non-empty-string $format
 * @return non-empty-string
 */
function packBytes(mixed $value, string $format): string
{
    /** @var non-empty-string */
    return pack($format, $value);
}
