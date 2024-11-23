<?php

use MatanYadaev\EloquentSpatial\EloquentSpatial;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestExtendedPlace;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedPoint;

it('creates a model record with point', function (): void {
    $point = new Point(0, 180);

    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['point' => $point]);

    expect($testPlace->point)->toBeInstanceOf(Point::class);
    expect($testPlace->point)->toEqual($point);
});

it('creates a model record with point with SRID integer', function (): void {
    $point = new Point(0, 180, Srid::WGS84->value);

    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['point' => $point]);

    expect($testPlace->point->srid)->toBe(Srid::WGS84->value);
});

it('creates a model record with point with SRID enum', function (): void {
    $point = new Point(0, 180, Srid::WGS84);

    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['point' => $point]);

    expect($testPlace->point->srid)->toBe(Srid::WGS84->value);
});

it('creates point with default 0 SRID from JSON', function (): void {
    // Arrange
    EloquentSpatial::setDefaultSrid(0);
    $point = new Point(0, 180);

    // Act
    $pointFromJson = Point::fromJson('{"type":"Point","coordinates":[180,0]}');

    // Assert
    expect($pointFromJson)->toEqual($point);
    expect($pointFromJson->srid)->toBe(0);
});

it('creates point with default 4326 SRID from JSON', function (): void {
    // Arrange
    EloquentSpatial::setDefaultSrid(Srid::WGS84);
    $point = new Point(0, 180);

    // Act
    $pointFromJson = Point::fromJson('{"type":"Point","coordinates":[180,0]}');

    // Assert
    expect($pointFromJson)->toEqual($point);
    expect($pointFromJson->srid)->toBe(Srid::WGS84->value);

    // Cleanup
    EloquentSpatial::setDefaultSrid(0);
});

it('creates point with SRID from JSON', function (): void {
    $point = new Point(0, 180, Srid::WGS84->value);

    $pointFromJson = Point::fromJson('{"type":"Point","coordinates":[180,0]}', Srid::WGS84->value);

    expect($pointFromJson)->toEqual($point);
});

it('creates point with default 0 SRID from array', function (): void {
    // Arrange
    EloquentSpatial::setDefaultSrid(0);
    $point = new Point(0, 180);

    // Act
    $pointFromJson = Point::fromArray(['type' => 'Point', 'coordinates' => [180, 0]]);

    // Assert
    expect($pointFromJson)->toEqual($point);
    expect($pointFromJson->srid)->toBe(0);
});

it('creates point with default 4326 SRID from array', function (): void {
    // Arrange
    EloquentSpatial::setDefaultSrid(Srid::WGS84);
    $point = new Point(0, 180);

    // Act
    $pointFromJson = Point::fromArray(['type' => 'Point', 'coordinates' => [180, 0]]);

    // Assert
    expect($pointFromJson)->toEqual($point);
    expect($pointFromJson->srid)->toBe(Srid::WGS84->value);

    // Cleanup
    EloquentSpatial::setDefaultSrid(0);
});

it('creates point with SRID from array', function (): void {
    $point = new Point(0, 180, Srid::WGS84->value);

    $pointFromJson = Point::fromArray(['type' => 'Point', 'coordinates' => [180, 0]], Srid::WGS84->value);

    expect($pointFromJson)->toEqual($point);
});

it('generates point JSON', function (): void {
    $point = new Point(0, 180);

    $json = $point->toJson();

    $expectedJson = '{"type":"Point","coordinates":[180,0]}';
    expect($json)->toBe($expectedJson);
});

it('throws exception when creating point from invalid JSON', function (): void {
    expect(function (): void {
        Point::fromJson('{"type":"Point","coordinates":[]}');
    })->toThrow(InvalidArgumentException::class);
});

it('creates point with default 0 SRID from WKT', function (): void {
    // Arrange
    EloquentSpatial::setDefaultSrid(0);
    $point = new Point(0, 180);

    $pointFromWkt = Point::fromWkt('POINT(180 0)');

    expect($pointFromWkt)->toEqual($point);
    expect($pointFromWkt->srid)->toBe(0);
});

it('creates point with default 4326 SRID from WKT', function (): void {
    // Arrange
    EloquentSpatial::setDefaultSrid(Srid::WGS84);
    $point = new Point(0, 180);

    // Act
    $pointFromWkt = Point::fromWkt('POINT(180 0)');

    // Assert
    expect($pointFromWkt)->toEqual($point);
    expect($pointFromWkt->srid)->toBe(Srid::WGS84->value);

    // Cleanup
    EloquentSpatial::setDefaultSrid(0);
});

it('creates point with SRID from WKT', function (): void {
    $point = new Point(0, 180, Srid::WGS84->value);

    $pointFromWkt = Point::fromWkt('POINT(180 0)', Srid::WGS84->value);

    expect($pointFromWkt)->toEqual($point);
});

it('generates point WKT', function (): void {
    $point = new Point(0, 180);

    $wkt = $point->toWkt();

    $expectedWkt = 'POINT(180 0)';
    expect($wkt)->toBe($expectedWkt);
});

it('creates point from WKB', function (): void {
    $point = new Point(0, 180);

    $pointFromWkb = Point::fromWkb($point->toWkb());

    expect($pointFromWkb)->toEqual($point);
});

it('creates point with SRID from WKB', function (): void {
    $point = new Point(0, 180, Srid::WGS84->value);

    $pointFromWkb = Point::fromWkb($point->toWkb());

    expect($pointFromWkb)->toEqual($point);
});

it('casts a Point to a string', function (): void {
    $point = new Point(0, 180, Srid::WGS84->value);

    expect($point->__toString())->toEqual('POINT(180 0)');
});

it('adds a macro toPoint', function (): void {
    Geometry::macro('getName', function (): string {
        /** @var Geometry $this */
        return class_basename($this);
    });

    $point = new Point(0, 180, Srid::WGS84->value);

    // @phpstan-ignore-next-line
    expect($point->getName())->toBe('Point');
});

it('uses an extended Point class', function (): void {
    // Arrange
    EloquentSpatial::usePoint(ExtendedPoint::class);
    $point = new ExtendedPoint(0, 180, 4326);

    // Act
    /** @var TestExtendedPlace $testPlace */
    $testPlace = TestExtendedPlace::factory()->create(['point' => $point])->fresh();

    // Assert
    expect($testPlace->point)->toBeInstanceOf(ExtendedPoint::class);
    expect($testPlace->point)->toEqual($point);
});

it('throws exception when storing a record with regular Point instead of the extended one', function (): void {
    // Arrange
    EloquentSpatial::usePoint(ExtendedPoint::class);
    $point = new Point(0, 180, 4326);

    // Act & Assert
    expect(function () use ($point): void {
        TestExtendedPlace::factory()->create(['point' => $point]);
    })->toThrow(InvalidArgumentException::class);
});

it('throws exception when storing a record with extended Point instead of the regular one', function (): void {
    // Arrange
    EloquentSpatial::usePoint(Point::class);
    $point = new ExtendedPoint(0, 180, 4326);

    // Act & Assert
    expect(function () use ($point): void {
        TestPlace::factory()->create(['point' => $point]);
    })->toThrow(InvalidArgumentException::class);
});
