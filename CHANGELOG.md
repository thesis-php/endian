# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.4.0] 2026-04-22

### Changed

* **BC break:** `packInt64` now accepts `int` instead of `BcMath\Number`.
* **BC break:** `unpackInt64` now returns `int` instead of `BcMath\Number`.
* Require a 64-bit PHP platform (`php-64bit: *` in `composer.json`).

## [0.3.3] 2026-04-09

### Added

* Replace `@param` PHPDoc types with `assert()`-based runtime bounds checks in all `pack*` methods.

### Changed

* `packUint64`/`unpackUint64` no longer iterate byte-by-byte with BcMath.
  Values are now split into two `uint32` chunks and handled with native `pack()`/`unpack()`.

### Deprecated

* `Int64` and `Uint64` type aliases declared in `Order`.

## [0.3.2] 2026-04-06

### Deprecated

* `Order::big` in favor of `Order::Big`
* `Order::little` in favor of `Order::Little`
* `Order::network` in favor of `Order::Network`

## [0.3.0] 2025-11-25

### Changed

* Use `bcmath` for `int64/uint64`.
* Bump to php 8.4.

## [0.2.0] 2025-11-24

### Changed

* Rename `Endian\endian` to `Endian\Order`.
