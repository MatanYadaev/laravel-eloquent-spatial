<?php

use MatanYadaev\EloquentSpatial\EloquentSpatial;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\GeometryCollection;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestExtendedPlace;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedGeometryCollection;

it('creates a model record with geometry collection', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['geometry_collection' => $geometryCollection]);

    // Assert
    expect($testPlace->geometry_collection)->toBeInstanceOf(GeometryCollection::class);
    expect($testPlace->geometry_collection)->toEqual($geometryCollection);
});

it('creates a model record with geometry collection with SRID integer', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ], Srid::WGS84->value);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['geometry_collection' => $geometryCollection]);

    // Assert
    expect($testPlace->geometry_collection->srid)->toBe(Srid::WGS84->value);
});

it('creates a model record with geometry collection with SRID enum', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ], Srid::WGS84);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['geometry_collection' => $geometryCollection]);

    // Assert
    expect($testPlace->geometry_collection->srid)->toBe(Srid::WGS84->value);
});

it('creates geometry collection with default 0 SRID from JSON', function (): void {
    // Arrange
    EloquentSpatial::setDefaultSrid(0);
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]);

    // Act
    $geometryCollectionFromJson = GeometryCollection::fromJson('{"type":"GeometryCollection","geometries":[{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]},{"type":"Point","coordinates":[180,0]}]}');

    // Assert
    expect($geometryCollectionFromJson)->toEqual($geometryCollection);
    expect($geometryCollectionFromJson->srid)->toBe(0);
});

it('creates geometry collection with default 4326 SRID from JSON', function (): void {
    // Arrange
    EloquentSpatial::setDefaultSrid(Srid::WGS84);
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]);

    // Act
    $geometryCollectionFromJson = GeometryCollection::fromJson('{"type":"GeometryCollection","geometries":[{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]},{"type":"Point","coordinates":[180,0]}]}');

    // Assert
    expect($geometryCollectionFromJson->toWkt())->toBe($geometryCollection->toWkt());
    expect($geometryCollectionFromJson->srid)->toBe(Srid::WGS84->value);

    // Cleanup
    EloquentSpatial::setDefaultSrid(0);
});

it('creates geometry collection with SRID from JSON', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ], Srid::WGS84->value);

    // Act
    $geometryCollectionFromJson = GeometryCollection::fromJson('{"type":"GeometryCollection","geometries":[{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]},{"type":"Point","coordinates":[180,0]}]}', Srid::WGS84->value);

    // Assert
    expect($geometryCollectionFromJson)->toEqual($geometryCollection);
});

it('creates geometry collection with default 0 SRID from array', function (): void {
    // Arrange
    EloquentSpatial::setDefaultSrid(0);
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]);

    // Act
    $geometryCollectionFromJson = GeometryCollection::fromArray(json_decode('{"type":"GeometryCollection","geometries":[{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]},{"type":"Point","coordinates":[180,0]}]}', true));

    // Assert
    expect($geometryCollectionFromJson)->toEqual($geometryCollection);
    expect($geometryCollectionFromJson->srid)->toBe(0);
});

it('creates geometry collection with default 4326 SRID from array', function (): void {
    // Arrange
    EloquentSpatial::setDefaultSrid(Srid::WGS84);
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]);

    // Act
    $geometryCollectionFromJson = GeometryCollection::fromArray(json_decode('{"type":"GeometryCollection","geometries":[{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]},{"type":"Point","coordinates":[180,0]}]}', true));

    // Assert
    expect($geometryCollectionFromJson->toWkt())->toBe($geometryCollection->toWkt());
    expect($geometryCollectionFromJson->srid)->toBe(Srid::WGS84->value);

    // Cleanup
    EloquentSpatial::setDefaultSrid(0);
});

it('creates geometry collection with SRID from array', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ], Srid::WGS84->value);

    // Act
    $geometryCollectionFromJson = GeometryCollection::fromArray(json_decode('{"type":"GeometryCollection","geometries":[{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]},{"type":"Point","coordinates":[180,0]}]}', true), Srid::WGS84->value);

    // Assert
    expect($geometryCollectionFromJson)->toEqual($geometryCollection);
});

it('creates geometry collection from feature collection JSON', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]);

    // Act
    $geometryCollectionFromFeatureCollectionJson = GeometryCollection::fromJson('{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}},{"type":"Feature","properties":[],"geometry":{"type":"Point","coordinates":[180,0]}}]}');

    // Assert
    expect($geometryCollectionFromFeatureCollectionJson)->toEqual($geometryCollection);
});

it('creates geometry collection from feature collection with SRID from JSON', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ], Srid::WGS84);

    // Act
    $geometryCollectionFromFeatureCollectionJson = GeometryCollection::fromJson('{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}},{"type":"Feature","properties":[],"geometry":{"type":"Point","coordinates":[180,0]}}]}', Srid::WGS84);

    // Assert
    expect($geometryCollectionFromFeatureCollectionJson)->toEqual($geometryCollection);
});

it('creates geometry collection from feature collection from array', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]);

    // Act
    $geometryCollectionFromFeatureCollectionJson = GeometryCollection::fromArray(json_decode('{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}},{"type":"Feature","properties":[],"geometry":{"type":"Point","coordinates":[180,0]}}]}', true));

    // Assert
    expect($geometryCollectionFromFeatureCollectionJson)->toEqual($geometryCollection);
});

it('creates geometry collection from feature collection with SRID from array', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ], Srid::WGS84);

    // Act
    $geometryCollectionFromFeatureCollectionJson = GeometryCollection::fromArray(json_decode('{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}},{"type":"Feature","properties":[],"geometry":{"type":"Point","coordinates":[180,0]}}]}', true), Srid::WGS84);

    // Assert
    expect($geometryCollectionFromFeatureCollectionJson)->toEqual($geometryCollection);
});

it('generates geometry collection JSON', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]);

    // Act
    $json = $geometryCollection->toJson();

    // Assert
    $expectedJson = '{"type":"GeometryCollection","geometries":[{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]},{"type":"Point","coordinates":[180,0]}]}';
    expect($json)->toBe($expectedJson);
});

it('generates geometry collection feature collection JSON', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]);

    // Act
    $featureCollectionJson = $geometryCollection->toFeatureCollectionJson();

    // Assert
    $expectedFeatureCollectionJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}},{"type":"Feature","properties":[],"geometry":{"type":"Point","coordinates":[180,0]}}]}';
    expect($featureCollectionJson)->toBe($expectedFeatureCollectionJson);
});

it('creates geometry collection with default 0 SRID from WKT', function (): void {
    // Arrange
    EloquentSpatial::setDefaultSrid(0);
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]);

    // Act
    $geometryCollectionFromWkt = GeometryCollection::fromWkt('GEOMETRYCOLLECTION(POLYGON((180 0, 179 1, 178 2, 177 3, 180 0)), POINT(180 0))');

    // Assert
    expect($geometryCollectionFromWkt)->toEqual($geometryCollection);
});

it('creates geometry collection with default 4326 SRID from WKT', function (): void {
    // Arrange
    EloquentSpatial::setDefaultSrid(Srid::WGS84);
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]);

    // Act
    $geometryCollectionFromWkt = GeometryCollection::fromWkt('GEOMETRYCOLLECTION(POLYGON((180 0, 179 1, 178 2, 177 3, 180 0)), POINT(180 0))');

    // Assert
    expect($geometryCollectionFromWkt->toWkt())->toBe($geometryCollection->toWkt());
    expect($geometryCollectionFromWkt->srid)->toBe(Srid::WGS84->value);

    // Cleanup
    EloquentSpatial::setDefaultSrid(0);
});

it('creates geometry collection with SRID from WKT', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ], Srid::WGS84->value);

    // Act
    $geometryCollectionFromWkt = GeometryCollection::fromWkt('GEOMETRYCOLLECTION(POLYGON((180 0, 179 1, 178 2, 177 3, 180 0)), POINT(180 0))', Srid::WGS84->value);

    // Assert
    expect($geometryCollectionFromWkt)->toEqual($geometryCollection);
});

it('creates geometry collection from EWKT', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ], Srid::WGS84->value);

    // Act
    $geometryCollectionFromEwkt = GeometryCollection::fromWkt('SRID=4326;GEOMETRYCOLLECTION(POLYGON((180 0, 179 1, 178 2, 177 3, 180 0)), POINT(180 0))');

    // Assert
    expect($geometryCollectionFromEwkt)->toEqual($geometryCollection);
    expect($geometryCollectionFromEwkt->srid)->toBe(Srid::WGS84->value);
});

it('creates empty geometry collection from WKT', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([]);

    // Act
    $geometryCollectionFromWkt = GeometryCollection::fromWkt('GEOMETRYCOLLECTION EMPTY');

    // Assert
    expect($geometryCollectionFromWkt)->toEqual($geometryCollection);
});

it('generates geometry collection WKT', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]);

    // Act
    $wkt = $geometryCollection->toWkt();

    // Assert
    $expectedWkt = 'GEOMETRYCOLLECTION(POLYGON((180 0, 179 1, 178 2, 177 3, 180 0)), POINT(180 0))';
    expect($wkt)->toBe($expectedWkt);
});

it('generates empty geometry collection WKT', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([]);

    // Act
    $wkt = $geometryCollection->toWkt();

    // Assert
    expect($wkt)->toBe('GEOMETRYCOLLECTION EMPTY');
});

it('creates geometry collection from WKB', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]);

    // Act
    $geometryCollectionFromWkb = GeometryCollection::fromWkb($geometryCollection->toWkb());

    // Assert
    expect($geometryCollectionFromWkb)->toEqual($geometryCollection);
});

it('creates geometry collection with SRID from WKB', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ], Srid::WGS84->value);

    // Act
    $geometryCollectionFromWkb = GeometryCollection::fromWkb($geometryCollection->toWkb());

    // Assert
    expect($geometryCollectionFromWkb)->toEqual($geometryCollection);
});

it('does not throw exception when geometry collection has no geometries', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([]);

    // Act
    $geometries = $geometryCollection->getGeometries();

    // Assert
    expect($geometries)->toHaveCount(0);
});

it('unsets geometry collection item', function (): void {
    // Arrange
    $point = new Point(0, 180);
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        $point,
    ]);

    // Act
    unset($geometryCollection[0]);

    // Assert
    expect($geometryCollection[0])->toBe($point);
    expect($geometryCollection->getGeometries())->toHaveCount(1);
});

it('throws exception when unsetting geometry collection item below minimum', function (): void {
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
    $act = static function () use ($polygon): void {
        unset($polygon[0]);
    };

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('checks if geometry collection item is exists', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]);

    // Act
    $firstItemExists = isset($geometryCollection[0]);
    $secondItemExists = isset($geometryCollection[1]);
    $thirdItemExists = isset($geometryCollection[2]);

    // Assert
    expect($firstItemExists)->toBeTrue();
    expect($secondItemExists)->toBeTrue();
    expect($thirdItemExists)->toBeFalse();
});

it('sets item to geometry collection', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]);
    $lineString = new LineString([
        new Point(0, 180),
        new Point(1, 179),
    ]);

    // Act
    $geometryCollection[2] = $lineString;

    // Assert
    expect($geometryCollection[2])->toBe($lineString);
});

it('throws exception when setting invalid item to geometry collection', function (): void {
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
    $act = static function () use ($polygon): void {
        // @phpstan-ignore-next-line
        $polygon[1] = new Point(0, 180);
    };

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('casts a GeometryCollection to a string', function (): void {
    // Arrange
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]);

    // Act
    $string = $geometryCollection->__toString();

    // Assert
    expect($string)->toEqual('GEOMETRYCOLLECTION(POLYGON((180 0, 179 1, 178 2, 177 3, 180 0)), POINT(180 0))');
});

it('adds a macro toGeometryCollection', function (): void {
    // Arrange
    Geometry::macro('getName', function (): string {
        /** @var Geometry $this */
        return class_basename($this);
    });

    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ]);

    // Act
    // @phpstan-ignore-next-line
    $name = $geometryCollection->getName();

    // Assert
    expect($name)->toBe('GeometryCollection');
});

it('uses an extended GeometryCollection class', function (): void {
    // Arrange
    EloquentSpatial::useGeometryCollection(ExtendedGeometryCollection::class);
    $geometryCollection = new ExtendedGeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ], 4326);

    // Act
    /** @var TestExtendedPlace $testPlace */
    $testPlace = TestExtendedPlace::factory()->create(['geometry_collection' => $geometryCollection])->fresh();

    // Assert
    expect($testPlace->geometry_collection)->toBeInstanceOf(ExtendedGeometryCollection::class);
    expect($testPlace->geometry_collection)->toEqual($geometryCollection);
});

it('throws exception when storing a record with regular GeometryCollection instead of the extended one', function (): void {
    // Arrange
    EloquentSpatial::useGeometryCollection(ExtendedGeometryCollection::class);
    $geometryCollection = new GeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ], 4326);

    // Act
    $act = static function () use ($geometryCollection): void {
        TestExtendedPlace::factory()->create(['geometry_collection' => $geometryCollection]);
    };

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('throws exception when storing a record with extended GeometryCollection instead of the regular one', function (): void {
    // Arrange
    EloquentSpatial::useGeometryCollection(ExtendedGeometryCollection::class);
    $geometryCollection = new ExtendedGeometryCollection([
        new Polygon([
            new LineString([
                new Point(0, 180),
                new Point(1, 179),
                new Point(2, 178),
                new Point(3, 177),
                new Point(0, 180),
            ]),
        ]),
        new Point(0, 180),
    ], 4326);

    // Act
    $act = static function () use ($geometryCollection): void {
        TestPlace::factory()->create(['geometry_collection' => $geometryCollection]);
    };

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});
