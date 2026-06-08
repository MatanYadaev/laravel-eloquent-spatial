<?php

use MatanYadaev\EloquentSpatial\EloquentSpatial;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestExtendedPlace;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedMultiPolygon;

it('creates a model record with multi polygon', function (): void {
    // Arrange
    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ]);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['multi_polygon' => $multiPolygon]);

    // Assert
    expect($testPlace->multi_polygon)->toBeInstanceOf(MultiPolygon::class);
    expect($testPlace->multi_polygon)->toEqual($multiPolygon);
});

it('creates a model record with multi polygon with SRID integer', function (): void {
    // Arrange
    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ], Srid::WGS84->value);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['multi_polygon' => $multiPolygon]);

    // Assert
    expect($testPlace->multi_polygon->srid)->toBe(Srid::WGS84->value);
});

it('creates a model record with multi polygon with SRID enum', function (): void {
    // Arrange
    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ], Srid::WGS84);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['multi_polygon' => $multiPolygon]);

    // Assert
    expect($testPlace->multi_polygon->srid)->toBe(Srid::WGS84->value);
});

it('creates multi polygon from JSON', function (): void {
    // Arrange
    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ]);

    // Act
    $multiPolygonFromJson = MultiPolygon::fromJson('{"type":"MultiPolygon","coordinates":[[[[180,0],[179,1],[178,2],[177,3],[180,0]]]]}');

    // Assert
    expect($multiPolygonFromJson)->toEqual($multiPolygon);
});

it('creates multi polygon with SRID from JSON', function (): void {
    // Arrange
    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ], Srid::WGS84->value);

    // Act
    $multiPolygonFromJson = MultiPolygon::fromJson('{"type":"MultiPolygon","coordinates":[[[[180,0],[179,1],[178,2],[177,3],[180,0]]]]}', Srid::WGS84->value);

    // Assert
    expect($multiPolygonFromJson)->toEqual($multiPolygon);
});

it('creates multi polygon from array', function (): void {
    // Arrange
    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ]);

    // Act
    $multiPolygonFromJson = MultiPolygon::fromArray(['type' => 'MultiPolygon', 'coordinates' => [[[[180, 0], [179, 1], [178, 2], [177, 3], [180, 0]]]]]);

    // Assert
    expect($multiPolygonFromJson)->toEqual($multiPolygon);
});

it('creates multi polygon with SRID from array', function (): void {
    // Arrange
    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ], Srid::WGS84->value);

    // Act
    $multiPolygonFromJson = MultiPolygon::fromArray(['type' => 'MultiPolygon', 'coordinates' => [[[[180, 0], [179, 1], [178, 2], [177, 3], [180, 0]]]]], Srid::WGS84->value);

    // Assert
    expect($multiPolygonFromJson)->toEqual($multiPolygon);
});

it('generates multi polygon JSON', function (): void {
    // Arrange
    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ]);

    // Act
    $json = $multiPolygon->toJson();

    // Assert
    $expectedJson = '{"type":"MultiPolygon","coordinates":[[[[180,0],[179,1],[178,2],[177,3],[180,0]]]]}';
    expect($json)->toBe($expectedJson);
});

it('generates multi polygon feature collection JSON', function (): void {
    // Arrange
    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ]);

    // Act
    $featureCollectionJson = $multiPolygon->toFeatureCollectionJson();

    // Assert
    $expectedFeatureCollectionJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"MultiPolygon","coordinates":[[[[180,0],[179,1],[178,2],[177,3],[180,0]]]]}}]}';
    expect($featureCollectionJson)->toBe($expectedFeatureCollectionJson);
});

it('creates multi polygon from WKT', function (): void {
    // Arrange
    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ]);

    // Act
    $multiPolygonFromWkt = MultiPolygon::fromWkt('MULTIPOLYGON(((180 0, 179 1, 178 2, 177 3, 180 0)))');

    // Assert
    expect($multiPolygonFromWkt)->toEqual($multiPolygon);
});

it('creates multi polygon with SRID from WKT', function (): void {
    // Arrange
    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ], Srid::WGS84->value);

    // Act
    $multiPolygonFromWkt = MultiPolygon::fromWkt('MULTIPOLYGON(((180 0, 179 1, 178 2, 177 3, 180 0)))', Srid::WGS84->value);

    // Assert
    expect($multiPolygonFromWkt)->toEqual($multiPolygon);
});

it('creates multi polygon from EWKT', function (): void {
    // Arrange
    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ], Srid::WGS84->value);

    // Act
    $multiPolygonFromEwkt = MultiPolygon::fromWkt('SRID=4326;MULTIPOLYGON(((180 0, 179 1, 178 2, 177 3, 180 0)))');

    // Assert
    expect($multiPolygonFromEwkt)->toEqual($multiPolygon);
    expect($multiPolygonFromEwkt->srid)->toBe(Srid::WGS84->value);
});

it('generates multi polygon WKT', function (): void {
    // Arrange
    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ]);

    // Act
    $wkt = $multiPolygon->toWkt();

    // Assert
    $expectedWkt = 'MULTIPOLYGON(((180 0, 179 1, 178 2, 177 3, 180 0)))';
    expect($wkt)->toBe($expectedWkt);
});

it('creates multi polygon from WKB', function (): void {
    // Arrange
    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ]);

    // Act
    $multiPolygonFromWkb = MultiPolygon::fromWkb($multiPolygon->toWkb());

    // Assert
    expect($multiPolygonFromWkb)->toEqual($multiPolygon);
});

it('creates multi polygon with SRID from WKB', function (): void {
    // Arrange
    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ], Srid::WGS84->value);

    // Act
    $multiPolygonFromWkb = MultiPolygon::fromWkb($multiPolygon->toWkb());

    // Assert
    expect($multiPolygonFromWkb)->toEqual($multiPolygon);
});

it('throws exception when multi polygon has no polygons', function (): void {
    // Act
    $act = static fn () => new MultiPolygon([]);

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('throws exception when creating multi polygon from incorrect geometry', function (): void {
    // Act
    $act = static fn () => new MultiPolygon([
        // @phpstan-ignore-next-line
        new Point(0, 0),
    ]);

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('casts a MultiPolygon to a string', function (): void {
    // Arrange
    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ]);

    // Act
    $string = $multiPolygon->__toString();

    // Assert
    expect($string)->toEqual('MULTIPOLYGON(((180 0, 179 1, 178 2, 177 3, 180 0)))');
});

it('adds a macro toMultiPolygon', function (): void {
    // Arrange
    Geometry::macro('getName', function (): string {
        /** @var Geometry $this */
        return class_basename($this);
    });

    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ]);

    // Act
    // @phpstan-ignore-next-line
    $name = $multiPolygon->getName();

    // Assert
    expect($name)->toBe('MultiPolygon');
});

it('uses an extended MultiPolygon class', function (): void {
    // Arrange
    EloquentSpatial::useMultiPolygon(ExtendedMultiPolygon::class);
    $multiPolygon = new ExtendedMultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ], 4326);

    // Act
    /** @var TestExtendedPlace $testPlace */
    $testPlace = TestExtendedPlace::factory()->create(['multi_polygon' => $multiPolygon])->fresh();

    // Assert
    expect($testPlace->multi_polygon)->toBeInstanceOf(ExtendedMultiPolygon::class);
    expect($testPlace->multi_polygon)->toEqual($multiPolygon);
});

it('throws exception when storing a record with regular MultiPolygon instead of the extended one', function (): void {
    // Arrange
    EloquentSpatial::useMultiPolygon(ExtendedMultiPolygon::class);
    $multiPolygon = new MultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ], 4326);

    // Act
    $act = static function () use ($multiPolygon): void {
        TestExtendedPlace::factory()->create(['multi_polygon' => $multiPolygon]);
    };

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('throws exception when storing a record with extended MultiPolygon instead of the regular one', function (): void {
    // Arrange
    EloquentSpatial::useMultiPolygon(MultiPolygon::class);
    $multiPolygon = new ExtendedMultiPolygon([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
    ], 4326);

    // Act
    $act = static function () use ($multiPolygon): void {
        TestPlace::factory()->create(['multi_polygon' => $multiPolygon]);
    };

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});
