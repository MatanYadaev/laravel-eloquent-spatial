# Changelog

All notable changes to `laravel-eloquent-spatial` will be documented in this file.

## v3.2.1 - 2023-12-28

- Fixed model serialization bug #100

## v3.2.0 - 2023-06-25

### What's Changed

- Support `St_Centroid` spatial function

**Full Changelog**: https://github.com/MatanYadaev/laravel-eloquent-spatial/compare/3.1.2...3.2.0

## v2.10.1 - 2023-03-09

### What's Changed

- Bugfix: Model spatial column update using Expression by @hiteshsamcom in https://github.com/MatanYadaev/laravel-eloquent-spatial/pull/86

### New Contributors

- @hiteshsamcom made their first contribution in https://github.com/MatanYadaev/laravel-eloquent-spatial/pull/86

**Full Changelog**: https://github.com/MatanYadaev/laravel-eloquent-spatial/compare/2.10.0...2.10.1

## v3.1.2 - 2023-02-28

### What's Changed

- Add SRID Enum by @Riley19280 in https://github.com/MatanYadaev/laravel-eloquent-spatial/pull/84

**Full Changelog**: https://github.com/MatanYadaev/laravel-eloquent-spatial/compare/3.1.1...3.1.2

## v3.1.1 - 2023-02-16

### What's Changed

- Bugfix: Model spatial column update using Expression by @MatanYadaev in https://github.com/MatanYadaev/laravel-eloquent-spatial/pull/81

**Full Changelog**: https://github.com/MatanYadaev/laravel-eloquent-spatial/compare/3.1.0...3.1.1

## v3.1.0 - 2023-02-06

### What's Changed

- Support receiving `Expression` as a parameter in `SpatialBuilder` methods by @Riley19280 in https://github.com/MatanYadaev/laravel-eloquent-spatial/pull/76

### New Contributors

- @Riley19280 made their first contribution in https://github.com/MatanYadaev/laravel-eloquent-spatial/pull/76

**Full Changelog**: https://github.com/MatanYadaev/laravel-eloquent-spatial/compare/3.0.0...3.1.0

## v3.0.0 - 2023-02-03

Support Larave 10

## v2.10.0 - 2023-01-25

Support PHP 8.2 #74

## v2.9.0 - 2023-01-09

Add HasSpatial trait #75

## v2.8.0 - 2022-12-12

- Cast geometry from array #71
- Add `Geometry@toSqlExpression` method #70

## v2.7.0 - 2022-10-06

Macroable geometry classes

## v2.6.0 - 2022-10-06

Add Doctrine types

## v2.5.2 - 2022-09-27

Query Builder - Support column to have table name #61

## v2.5.1 - 2022-09-24

Geometry objects are now stringable.

## v2.5.0 - 2022-09-23

Support MySQL 5.7

## v2.4.0 - 2022-09-22

- Add `whereNotWithin` Builder method
- Add `whereNotContains` Builder method

## v2.3.0 - 2022-09-06

Added MariaDB support

## v2.2.0 - 2022-08-29

Fix `axis-order` when using SRID

## v2.1.1 - 2022-07-20

Refactor `toWkt`

## v2.1.0 - 2022-07-20

- SRID support
- Add `Geometry@toWkt`
- Add `Geometry@toWkb`
- Fix generic type issue with `GeometryCollection`

## v2.0.1 - 2022-07-12

Make `Geometry::toWkt` parameters optional

## v2.0.0 - 2022-07-08

Improve casting:

- Fix `getOriginal`
- Use a more standard WKT format

## 1.0.4 - 2022-03-07

- Fix PHPStan issues

## 1.0.3 - 2022-02-24

- Support Laravel 9
- Support PHP 8.1

## 1.0.2 - 2021-08-31

- Remove auto-discovery part from composer.json

## 1.0.1 - 2021-08-11

- Upgrade to PHP-CS-Fixer v3 and clear PHPStan issues

## 1.0.0 - 2021-02-23

- Initial release
