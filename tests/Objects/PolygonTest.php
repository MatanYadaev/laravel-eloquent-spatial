<?php

use MatanYadaev\EloquentSpatial\EloquentSpatial;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestExtendedPlace;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedPolygon;

it('creates a model record with polygon', function (): void {
    // Arrange
    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ]);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['polygon' => $polygon]);

    // Assert
    expect($testPlace->polygon)->toBeInstanceOf(Polygon::class);
    expect($testPlace->polygon)->toEqual($polygon);
});

it('creates a model record with polygon with SRID integer', function (): void {
    // Arrange
    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ], Srid::WGS84->value);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['polygon' => $polygon]);

    // Assert
    expect($testPlace->polygon->srid)->toBe(Srid::WGS84->value);
});

it('creates a model record with polygon with SRID enum', function (): void {
    // Arrange
    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ], Srid::WGS84);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['polygon' => $polygon]);

    // Assert
    expect($testPlace->polygon->srid)->toBe(Srid::WGS84->value);
});

it('creates polygon from JSON', function (): void {
    // Arrange
    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ]);

    // Act
    $polygonFromJson = Polygon::fromJson('{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}');

    // Assert
    expect($polygonFromJson)->toEqual($polygon);
});

it('creates polygon with SRID from JSON', function (): void {
    // Arrange
    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ], Srid::WGS84->value);

    // Act
    $polygonFromJson = Polygon::fromJson('{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}', Srid::WGS84->value);

    // Assert
    expect($polygonFromJson)->toEqual($polygon);
});

it('creates polygon from array', function (): void {
    // Arrange
    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ]);

    // Act
    $polygonFromJson = Polygon::fromArray(['type' => 'Polygon', 'coordinates' => [[[180, 0], [179, 1], [178, 2], [177, 3], [180, 0]]]]);

    // Assert
    expect($polygonFromJson)->toEqual($polygon);
});

it('creates polygon with SRID from array', function (): void {
    // Arrange
    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ], Srid::WGS84->value);

    // Act
    $polygonFromJson = Polygon::fromArray(['type' => 'Polygon', 'coordinates' => [[[180, 0], [179, 1], [178, 2], [177, 3], [180, 0]]]], Srid::WGS84->value);

    // Assert
    expect($polygonFromJson)->toEqual($polygon);
});

it('generates polygon JSON', function (): void {
    // Arrange
    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ]);

    // Act
    $json = $polygon->toJson();

    // Assert
    $expectedJson = '{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}';
    expect($json)->toBe($expectedJson);
});

it('generates polygon feature collection JSON', function (): void {
    // Arrange
    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ]);

    // Act
    $featureCollectionJson = $polygon->toFeatureCollectionJson();

    // Assert
    $expectedFeatureCollectionJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}}]}';
    expect($featureCollectionJson)->toBe($expectedFeatureCollectionJson);
});

it('creates polygon from WKT', function (): void {
    // Arrange
    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ]);

    // Act
    $polygonFromWkt = Polygon::fromWkt('POLYGON((180 0, 179 1, 178 2, 177 3, 180 0))');

    // Assert
    expect($polygonFromWkt)->toEqual($polygon);
});

it('creates polygon with SRID from WKT', function (): void {
    // Arrange
    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ], Srid::WGS84->value);

    // Act
    $polygonFromWkt = Polygon::fromWkt('POLYGON((180 0, 179 1, 178 2, 177 3, 180 0))', Srid::WGS84->value);

    // Assert
    expect($polygonFromWkt)->toEqual($polygon);
});

it('creates polygon from EWKT', function (): void {
    // Arrange
    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ], Srid::WGS84->value);

    // Act
    $polygonFromEwkt = Polygon::fromWkt('SRID=4326;POLYGON((180 0, 179 1, 178 2, 177 3, 180 0))');

    // Assert
    expect($polygonFromEwkt)->toEqual($polygon);
    expect($polygonFromEwkt->srid)->toBe(Srid::WGS84->value);
});

it('generates polygon WKT', function (): void {
    // Arrange
    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ]);

    // Act
    $wkt = $polygon->toWkt();

    // Assert
    $expectedWkt = 'POLYGON((180 0, 179 1, 178 2, 177 3, 180 0))';
    expect($wkt)->toBe($expectedWkt);
});

it('creates polygon from WKB', function (): void {
    // Arrange
    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ]);

    // Act
    $polygonFromWkb = Polygon::fromWkb($polygon->toWkb());

    // Assert
    expect($polygonFromWkb)->toEqual($polygon);
});

it('creates polygon with SRID from WKB', function (): void {
    // Arrange
    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ], Srid::WGS84->value);

    // Act
    $polygonFromWkb = Polygon::fromWkb($polygon->toWkb());

    // Assert
    expect($polygonFromWkb)->toEqual($polygon);
});

it('throws exception when polygon has no line strings', function (): void {
    // Act
    $act = static fn () => new Polygon([]);

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('throws exception when creating polygon from incorrect geometry', function (): void {
    // Act
    // @phpstan-ignore-next-line
    $act = static fn () => new Polygon([
        new Point(0, 0),
    ]);

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('casts a Polygon to a string', function (): void {
    // Arrange
    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ]);

    // Act
    $string = $polygon->__toString();

    // Assert
    expect($string)->toEqual('POLYGON((180 0, 179 1, 178 2, 177 3, 180 0))');
});

it('adds a macro toPolygon', function (): void {
    // Arrange
    Geometry::macro('getName', function (): string {
        /** @var Geometry $this */
        return class_basename($this);
    });

    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ]);

    // Act
    // @phpstan-ignore-next-line
    $name = $polygon->getName();

    // Assert
    // @phpstan-ignore-next-line
    expect($name)->toBe('Polygon');
});

it('uses an extended Polygon class', function (): void {
    // Arrange
    EloquentSpatial::usePolygon(ExtendedPolygon::class);
    $polygon = new ExtendedPolygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ], 4326);

    // Act
    /** @var TestExtendedPlace $testPlace */
    $testPlace = TestExtendedPlace::factory()->create(['polygon' => $polygon])->fresh();

    // Assert
    expect($testPlace->polygon)->toBeInstanceOf(ExtendedPolygon::class);
    expect($testPlace->polygon)->toEqual($polygon);
});

it('throws exception when storing a record with regular Polygon instead of the extended one', function (): void {
    // Arrange
    EloquentSpatial::usePolygon(ExtendedPolygon::class);
    $polygon = new Polygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ], 4326);

    // Act
    $act = static fn () => TestExtendedPlace::factory()->create(['polygon' => $polygon]);

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('throws exception when storing a record with extended Polygon instead of the regular one', function (): void {
    // Arrange
    EloquentSpatial::usePolygon(Polygon::class);
    $polygon = new ExtendedPolygon([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
            new Point(2, 178),
            new Point(3, 177),
            new Point(0, 180),
        ]),
    ], 4326);

    // Act
    $act = static fn () => TestPlace::factory()->create(['polygon' => $polygon]);

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});
