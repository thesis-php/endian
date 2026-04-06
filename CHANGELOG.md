# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.3.3] 2026-04-06

### Changed

* Accept int8/16/32 as `int` uint8/16/32 as `non-negative-int` to simplify upstream libraries. 

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
