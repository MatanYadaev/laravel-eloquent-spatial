<?php

use MatanYadaev\EloquentSpatial\EloquentSpatial;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestExtendedPlace;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedLineString;

it('creates a model record with line string', function (): void {
    // Arrange
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ]);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['line_string' => $lineString]);

    // Assert
    expect($testPlace->line_string)->toBeInstanceOf(LineString::class);
    expect($testPlace->line_string)->toEqual($lineString);
});

it('creates a model record with line string with SRID integer', function (): void {
    // Arrange
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ], Srid::WGS84->value);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['line_string' => $lineString]);

    // Assert
    expect($testPlace->line_string->srid)->toBe(Srid::WGS84->value);
});

it('creates a model record with line string with SRID enum', function (): void {
    // Arrange
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ], Srid::WGS84);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['line_string' => $lineString]);

    // Assert
    expect($testPlace->line_string->srid)->toBe(Srid::WGS84->value);
});

it('creates line string from JSON', function (): void {
    // Arrange
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ]);

    // Act
    $lineStringFromJson = LineString::fromJson('{"type":"LineString","coordinates":[[180,0],[179,1]]}');

    // Assert
    expect($lineStringFromJson)->toEqual($lineString);
});

it('creates line string with SRID from JSON', function (): void {
    // Arrange
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ], Srid::WGS84->value);

    // Act
    $lineStringFromJson = LineString::fromJson('{"type":"LineString","coordinates":[[180,0],[179,1]]}', Srid::WGS84->value);

    // Assert
    expect($lineStringFromJson)->toEqual($lineString);
});

it('creates line string from array', function (): void {
    // Arrange
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ]);

    // Act
    $lineStringFromJson = LineString::fromArray(['type' => 'LineString', 'coordinates' => [[180, 0], [179, 1]]]);

    // Assert
    expect($lineStringFromJson)->toEqual($lineString);
});

it('creates line string with SRID from array', function (): void {
    // Arrange
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ], Srid::WGS84->value);

    // Act
    $lineStringFromJson = LineString::fromArray(['type' => 'LineString', 'coordinates' => [[180, 0], [179, 1]]], Srid::WGS84->value);

    // Assert
    expect($lineStringFromJson)->toEqual($lineString);
});

it('generates line string JSON', function (): void {
    // Arrange
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ]);

    // Act
    $json = $lineString->toJson();

    // Assert
    $expectedJson = '{"type":"LineString","coordinates":[[180,0],[179,1]]}';
    expect($json)->toBe($expectedJson);
});

it('generates line string feature collection JSON', function (): void {
    // Arrange
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ]);

    // Act
    $featureCollectionJson = $lineString->toFeatureCollectionJson();

    // Assert
    $expectedFeatureCollectionJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"LineString","coordinates":[[180,0],[179,1]]}}]}';
    expect($featureCollectionJson)->toBe($expectedFeatureCollectionJson);
});

it('creates line string from WKT', function (): void {
    // Arrange
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ]);

    // Act
    $lineStringFromWkt = LineString::fromWkt('LINESTRING(180 0, 179 1)');

    // Assert
    expect($lineStringFromWkt)->toEqual($lineString);
});

it('creates line string with SRID from WKT', function (): void {
    // Arrange
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ], Srid::WGS84->value);

    // Act
    $lineStringFromWkt = LineString::fromWkt('LINESTRING(180 0, 179 1)', Srid::WGS84->value);

    // Assert
    expect($lineStringFromWkt)->toEqual($lineString);
});

it('creates line string from EWKT', function (): void {
    // Arrange
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ], Srid::WGS84->value);

    // Act
    $lineStringFromEwkt = LineString::fromWkt('SRID=4326;LINESTRING(180 0, 179 1)');

    // Assert
    expect($lineStringFromEwkt)->toEqual($lineString);
    expect($lineStringFromEwkt->srid)->toBe(Srid::WGS84->value);
});

it('generates line string WKT', function (): void {
    // Arrange
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ]);

    // Act
    $wkt = $lineString->toWkt();

    // Assert
    $expectedWkt = 'LINESTRING(180 0, 179 1)';
    expect($wkt)->toBe($expectedWkt);
});

it('creates line string from WKB', function (): void {
    // Arrange
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ]);
    $lineStringWkb = $lineString->toWkb();

    // Act
    $lineStringFromWkb = LineString::fromWkb($lineStringWkb);

    // Assert
    expect($lineStringFromWkb)->toEqual($lineString);
});

it('creates line string with SRID from WKB', function (): void {
    // Arrange
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ], Srid::WGS84->value);
    $lineStringWkb = $lineString->toWkb();

    // Act
    $lineStringFromWkb = LineString::fromWkb($lineStringWkb);

    // Assert
    expect($lineStringFromWkb)->toEqual($lineString);
});

it('throws exception when line string has less than two points', function (): void {
    // Act
    $act = static fn () => new LineString([
        new Point(0, 180),
    ]);

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('throws exception when creating line string from incorrect geometry', function (): void {
    // Act
    // @phpstan-ignore-next-line
    $act = static fn () => new LineString([
        Polygon::fromJson('{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}'),
    ]);

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('casts a LineString to a string', function (): void {
    // Arrange
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ]);

    // Act
    $string = $lineString->__toString();

    // Assert
    expect($string)->toEqual('LINESTRING(180 0, 179 1)');
});

it('adds a macro toLineString', function (): void {
    // Arrange
    Geometry::macro('getName', function (): string {
        /** @var Geometry $this */
        return class_basename($this);
    });

    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ]);

    // Act
    // @phpstan-ignore-next-line
    $name = $lineString->getName();

    // Assert
    expect($name)->toBe('LineString');
});

it('uses an extended LineString class', function (): void {
    // Arrange
    EloquentSpatial::useLineString(ExtendedLineString::class);
    $lineString = new ExtendedLineString([
        new Point(0, 180),
        new Point(1, 179),
    ], 4326);

    // Act
    /** @var TestExtendedPlace $testPlace */
    $testPlace = TestExtendedPlace::factory()->create(['line_string' => $lineString])->fresh();

    // Assert
    expect($testPlace->line_string)->toBeInstanceOf(ExtendedLineString::class);
    expect($testPlace->line_string)->toEqual($lineString);
});

it('throws exception when storing a record with regular LineString instead of the extended one', function (): void {
    // Arrange
    EloquentSpatial::useLineString(ExtendedLineString::class);
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ], 4326);

    // Act
    $act = static fn () => TestExtendedPlace::factory()->create(['line_string' => $lineString]);

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('throws exception when storing a record with extended LineString instead of the regular one', function (): void {
    // Arrange
    EloquentSpatial::useLineString(LineString::class);
    $lineString = new ExtendedLineString([
        new Point(0, 180),
        new Point(1, 179),
    ], 4326);

    // Act
    $act = static fn () => TestPlace::factory()->create(['line_string' => $lineString]);

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});
