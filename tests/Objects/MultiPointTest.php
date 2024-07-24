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
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['multi_point' => $multiPoint]);

    expect($testPlace->multi_point)->toBeInstanceOf(MultiPoint::class);
    expect($testPlace->multi_point)->toEqual($multiPoint);
});

it('creates a model record with multi point with SRID integer', function (): void {
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ], Srid::WGS84->value);

    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['multi_point' => $multiPoint]);

    expect($testPlace->multi_point->srid)->toBe(Srid::WGS84->value);
});

it('creates a model record with multi point with SRID enum', function (): void {
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ], Srid::WGS84);

    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['multi_point' => $multiPoint]);

    expect($testPlace->multi_point->srid)->toBe(Srid::WGS84->value);
});

it('creates multi point from JSON', function (): void {
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    $multiPointFromJson = MultiPoint::fromJson('{"type":"MultiPoint","coordinates":[[180,0]]}');

    expect($multiPointFromJson)->toEqual($multiPoint);
});

it('creates multi point with SRID from JSON', function (): void {
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ], Srid::WGS84->value);

    $multiPointFromJson = MultiPoint::fromJson('{"type":"MultiPoint","coordinates":[[180,0]]}', Srid::WGS84->value);

    expect($multiPointFromJson)->toEqual($multiPoint);
});

it('creates multi point from array', function (): void {
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    $multiPointFromJson = MultiPoint::fromArray(['type' => 'MultiPoint', 'coordinates' => [[180, 0]]]);

    expect($multiPointFromJson)->toEqual($multiPoint);
});

it('creates multi point with SRID from array', function (): void {
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ], Srid::WGS84->value);

    $multiPointFromJson = MultiPoint::fromArray(['type' => 'MultiPoint', 'coordinates' => [[180, 0]]], Srid::WGS84->value);

    expect($multiPointFromJson)->toEqual($multiPoint);
});

it('generates multi point JSON', function (): void {
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    $json = $multiPoint->toJson();

    $expectedJson = '{"type":"MultiPoint","coordinates":[[180,0]]}';
    expect($json)->toBe($expectedJson);
});

it('generates multi point feature collection JSON', function (): void {
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    $multiPointFeatureCollectionJson = $multiPoint->toFeatureCollectionJson();

    $expectedFeatureCollectionJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"MultiPoint","coordinates":[[180,0]]}}]}';
    expect($multiPointFeatureCollectionJson)->toBe($expectedFeatureCollectionJson);
});

it('creates multi point from WKT', function (): void {
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    $multiPointFromWkt = MultiPoint::fromWkt('MULTIPOINT(180 0)');

    expect($multiPointFromWkt)->toEqual($multiPoint);
});

it('creates multi point with SRID from WKT', function (): void {
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ], Srid::WGS84->value);

    $multiPointFromWkt = MultiPoint::fromWkt('MULTIPOINT(180 0)', Srid::WGS84->value);

    expect($multiPointFromWkt)->toEqual($multiPoint);
});

it('generates multi point WKT', function (): void {
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    $wkt = $multiPoint->toWkt();

    $expectedWkt = 'MULTIPOINT(180 0)';
    expect($wkt)->toBe($expectedWkt);
});

it('creates multi point from WKB', function (): void {
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    $multiPointFromWkb = MultiPoint::fromWkb($multiPoint->toWkb());

    expect($multiPointFromWkb)->toEqual($multiPoint);
});

it('creates multi point with SRID from WKB', function (): void {
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ], Srid::WGS84->value);

    $multiPointFromWkb = MultiPoint::fromWkb($multiPoint->toWkb());

    expect($multiPointFromWkb)->toEqual($multiPoint);
});

it('throws exception when multi point has no points', function (): void {
    expect(function (): void {
        new MultiPoint([]);
    })->toThrow(InvalidArgumentException::class);
});

it('throws exception when creating multi point from incorrect geometry', function (): void {
    expect(function (): void {
        // @phpstan-ignore-next-line
        new MultiPoint([
            Polygon::fromJson('{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}'),
        ]);
    })->toThrow(InvalidArgumentException::class);
});

it('casts a MultiPoint to a string', function (): void {
    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    expect($multiPoint->__toString())->toEqual('MULTIPOINT(180 0)');
});

it('adds a macro toMultiPoint', function (): void {
    Geometry::macro('getName', function (): string {
        /** @var Geometry $this */
        return class_basename($this);
    });

    $multiPoint = new MultiPoint([
        new Point(0, 180),
    ]);

    // @phpstan-ignore-next-line
    expect($multiPoint->getName())->toBe('MultiPoint');
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

    // Act & Assert
    expect(function () use ($multiPoint): void {
        TestExtendedPlace::factory()->create(['multi_point' => $multiPoint]);
    })->toThrow(InvalidArgumentException::class);
});

it('throws exception when storing a record with extended MultiPoint instead of the regular one', function (): void {
    // Arrange
    EloquentSpatial::useMultiPoint(MultiPoint::class);
    $multiPoint = new ExtendedMultiPoint([
        new Point(0, 180),
    ], 4326);

    // Act & Assert
    expect(function () use ($multiPoint): void {
        TestPlace::factory()->create(['multi_point' => $multiPoint]);
    })->toThrow(InvalidArgumentException::class);
});
