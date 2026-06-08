<?php

use MatanYadaev\EloquentSpatial\EloquentSpatial;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestExtendedPlace;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedMultiPoint;

it('creates a model record with multi point', function (): void {
    // Arrange
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['multi_point' => $multiPoint]);

    // Assert
    expect($testPlace->multi_point)->toBeInstanceOf(MultiPoint::class);
    expect($testPlace->multi_point)->toEqual($multiPoint);
});

it('creates a model record with multi point with SRID integer', function (): void {
    // Arrange
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ], Srid::WGS84->value);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['multi_point' => $multiPoint]);

    // Assert
    expect($testPlace->multi_point->srid)->toBe(Srid::WGS84->value);
});

it('creates a model record with multi point with SRID enum', function (): void {
    // Arrange
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ], Srid::WGS84);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['multi_point' => $multiPoint]);

    // Assert
    expect($testPlace->multi_point->srid)->toBe(Srid::WGS84->value);
});

it('creates multi point from JSON', function (): void {
    // Arrange
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    // Act
    $multiPointFromJson = MultiPoint::fromJson('{"type":"MultiPoint","coordinates":[[180,0]]}');

    // Assert
    expect($multiPointFromJson)->toEqual($multiPoint);
});

it('creates multi point with SRID from JSON', function (): void {
    // Arrange
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ], Srid::WGS84->value);

    // Act
    $multiPointFromJson = MultiPoint::fromJson('{"type":"MultiPoint","coordinates":[[180,0]]}', Srid::WGS84->value);

    // Assert
    expect($multiPointFromJson)->toEqual($multiPoint);
});

it('creates multi point from array', function (): void {
    // Arrange
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    // Act
    $multiPointFromJson = MultiPoint::fromArray(['type' => 'MultiPoint', 'coordinates' => [[180, 0]]]);

    // Assert
    expect($multiPointFromJson)->toEqual($multiPoint);
});

it('creates multi point with SRID from array', function (): void {
    // Arrange
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ], Srid::WGS84->value);

    // Act
    $multiPointFromJson = MultiPoint::fromArray(['type' => 'MultiPoint', 'coordinates' => [[180, 0]]], Srid::WGS84->value);

    // Assert
    expect($multiPointFromJson)->toEqual($multiPoint);
});

it('generates multi point JSON', function (): void {
    // Arrange
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    // Act
    $json = $multiPoint->toJson();

    // Assert
    $expectedJson = '{"type":"MultiPoint","coordinates":[[180,0]]}';
    expect($json)->toBe($expectedJson);
});

it('generates multi point feature collection JSON', function (): void {
    // Arrange
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    // Act
    $multiPointFeatureCollectionJson = $multiPoint->toFeatureCollectionJson();

    // Assert
    $expectedFeatureCollectionJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"MultiPoint","coordinates":[[180,0]]}}]}';
    expect($multiPointFeatureCollectionJson)->toBe($expectedFeatureCollectionJson);
});

it('creates multi point from WKT', function (): void {
    // Arrange
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    // Act
    $multiPointFromWkt = MultiPoint::fromWkt('MULTIPOINT(180 0)');

    // Assert
    expect($multiPointFromWkt)->toEqual($multiPoint);
});

it('creates multi point with SRID from WKT', function (): void {
    // Arrange
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ], Srid::WGS84->value);

    // Act
    $multiPointFromWkt = MultiPoint::fromWkt('MULTIPOINT(180 0)', Srid::WGS84->value);

    // Assert
    expect($multiPointFromWkt)->toEqual($multiPoint);
});

it('creates multi point from EWKT', function (): void {
    // Arrange
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ], Srid::WGS84->value);

    // Act
    $multiPointFromEwkt = MultiPoint::fromWkt('SRID=4326;MULTIPOINT(180 0)');

    // Assert
    expect($multiPointFromEwkt)->toEqual($multiPoint);
    expect($multiPointFromEwkt->srid)->toBe(Srid::WGS84->value);
});

it('generates multi point WKT', function (): void {
    // Arrange
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    // Act
    $wkt = $multiPoint->toWkt();

    // Assert
    $expectedWkt = 'MULTIPOINT(180 0)';
    expect($wkt)->toBe($expectedWkt);
});

it('creates multi point from WKB', function (): void {
    // Arrange
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);
    $multiPointWkb = $multiPoint->toWkb();

    // Act
    $multiPointFromWkb = MultiPoint::fromWkb($multiPointWkb);

    // Assert
    expect($multiPointFromWkb)->toEqual($multiPoint);
});

it('creates multi point with SRID from WKB', function (): void {
    // Arrange
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ], Srid::WGS84->value);
    $multiPointWkb = $multiPoint->toWkb();

    // Act
    $multiPointFromWkb = MultiPoint::fromWkb($multiPointWkb);

    // Assert
    expect($multiPointFromWkb)->toEqual($multiPoint);
});

it('throws exception when multi point has no points', function (): void {
    // Act
    $act = static fn () => new MultiPoint([]);

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('throws exception when creating multi point from incorrect geometry', function (): void {
    // Act
    // @phpstan-ignore-next-line
    $act = static fn () => new MultiPoint([
        Polygon::fromJson('{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}'),
    ]);

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('casts a MultiPoint to a string', function (): void {
    // Arrange
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    // Act
    $string = $multiPoint->__toString();

    // Assert
    expect($string)->toEqual('MULTIPOINT(180 0)');
});

it('adds a macro toMultiPoint', function (): void {
    // Arrange
    Geometry::macro('getName', function (): string {
        /** @var Geometry $this */
        return class_basename($this);
    });

    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    // Act
    // @phpstan-ignore-next-line
    $name = $multiPoint->getName();

    // Assert
    // @phpstan-ignore-next-line
    expect($name)->toBe('MultiPoint');
});

it('uses an extended MultiPoint class', function (): void {
    // Arrange
    EloquentSpatial::useMultiPoint(ExtendedMultiPoint::class);
    $multiPoint = new ExtendedMultiPoint([
        new Point(0, 180),
    ], 4326);

    // Act
    /** @var TestExtendedPlace $testPlace */
    $testPlace = TestExtendedPlace::factory()->create(['multi_point' => $multiPoint])->fresh();

    // Assert
    expect($testPlace->multi_point)->toBeInstanceOf(ExtendedMultiPoint::class);
    expect($testPlace->multi_point)->toEqual($multiPoint);
});

it('throws exception when storing a record with regular MultiPoint instead of the extended one', function (): void {
    // Arrange
    EloquentSpatial::useMultiPoint(ExtendedMultiPoint::class);
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ], 4326);

    // Act
    $act = static fn () => TestExtendedPlace::factory()->create(['multi_point' => $multiPoint]);

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('throws exception when storing a record with extended MultiPoint instead of the regular one', function (): void {
    // Arrange
    EloquentSpatial::useMultiPoint(MultiPoint::class);
    $multiPoint = new ExtendedMultiPoint([
        new Point(0, 180),
    ], 4326);

    // Act
    $act = static fn () => TestPlace::factory()->create(['multi_point' => $multiPoint]);

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});
