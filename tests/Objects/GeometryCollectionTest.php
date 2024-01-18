<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\GeometryCollection;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

uses(DatabaseMigrations::class);

it('creates a model record with geometry collection', function (): void {
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

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['geometry_collection' => $geometryCollection]);

  expect($testPlace->geometry_collection)->toBeInstanceOf(GeometryCollection::class);
  expect($testPlace->geometry_collection)->toEqual($geometryCollection);
});

it('creates a model record with geometry collection with SRID integer', function (): void {
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

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['geometry_collection' => $geometryCollection]);

  expect($testPlace->geometry_collection->srid)->toBe(Srid::WGS84->value);
});

it('creates a model record with geometry collection with SRID enum', function (): void {
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

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['geometry_collection' => $geometryCollection]);

  expect($testPlace->geometry_collection->srid)->toBe(Srid::WGS84->value);
});

it('creates geometry collection from JSON', function (): void {
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

  $geometryCollectionFromJson = GeometryCollection::fromJson('{"type":"GeometryCollection","geometries":[{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]},{"type":"Point","coordinates":[180,0]}]}');

  expect($geometryCollectionFromJson)->toEqual($geometryCollection);
});

it('creates geometry collection with SRID from JSON', function (): void {
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

  $geometryCollectionFromJson = GeometryCollection::fromJson('{"type":"GeometryCollection","geometries":[{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]},{"type":"Point","coordinates":[180,0]}]}', Srid::WGS84->value);

  expect($geometryCollectionFromJson)->toEqual($geometryCollection);
});

it('creates geometry collection from feature collection JSON', function (): void {
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

  $geometryCollectionFromFeatureCollectionJson = GeometryCollection::fromJson('{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}},{"type":"Feature","properties":[],"geometry":{"type":"Point","coordinates":[180,0]}}]}');

  expect($geometryCollectionFromFeatureCollectionJson)->toEqual($geometryCollection);
});

it('generates geometry collection JSON', function (): void {
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

  $json = $geometryCollection->toJson();

  $expectedJson = '{"type":"GeometryCollection","geometries":[{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]},{"type":"Point","coordinates":[180,0]}]}';
  expect($json)->toBe($expectedJson);
});

it('generates geometry collection feature collection JSON', function (): void {
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

  $featureCollectionJson = $geometryCollection->toFeatureCollectionJson();

  $expectedFeatureCollectionJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}},{"type":"Feature","properties":[],"geometry":{"type":"Point","coordinates":[180,0]}}]}';
  expect($featureCollectionJson)->toBe($expectedFeatureCollectionJson);
});

it('creates geometry collection from WKT', function (): void {
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

  $geometryCollectionFromWkt = GeometryCollection::fromWkt('GEOMETRYCOLLECTION(POLYGON((180 0, 179 1, 178 2, 177 3, 180 0)), POINT(180 0))');

  expect($geometryCollectionFromWkt)->toEqual($geometryCollection);
});

it('creates geometry collection with SRID from WKT', function (): void {
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

  $geometryCollectionFromWkt = GeometryCollection::fromWkt('GEOMETRYCOLLECTION(POLYGON((180 0, 179 1, 178 2, 177 3, 180 0)), POINT(180 0))', Srid::WGS84->value);

  expect($geometryCollectionFromWkt)->toEqual($geometryCollection);
});

it('generates geometry collection WKT', function (): void {
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

  $wkt = $geometryCollection->toWkt();

  $expectedWkt = 'GEOMETRYCOLLECTION(POLYGON((180 0, 179 1, 178 2, 177 3, 180 0)), POINT(180 0))';
  expect($wkt)->toBe($expectedWkt);
});

it('creates geometry collection from WKB', function (): void {
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

  $geometryCollectionFromWkb = GeometryCollection::fromWkb($geometryCollection->toWkb());

  expect($geometryCollectionFromWkb)->toEqual($geometryCollection);
});

it('creates geometry collection with SRID from WKB', function (): void {
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

  $geometryCollectionFromWkb = GeometryCollection::fromWkb($geometryCollection->toWkb());

  expect($geometryCollectionFromWkb)->toEqual($geometryCollection);
});

it('does not throw exception when geometry collection has no geometries', function (): void {
  $geometryCollection = new GeometryCollection([]);

  expect($geometryCollection->getGeometries())->toHaveCount(0);
});

it('unsets geometry collection item', function (): void {
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

  unset($geometryCollection[0]);

  expect($geometryCollection[0])->toBe($point);
  expect($geometryCollection->getGeometries())->toHaveCount(1);
});

it('throws exception when unsetting geometry collection item below minimum', function (): void {
  $polygon = new Polygon([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
      new Point(2, 178),
      new Point(3, 177),
      new Point(0, 180),
    ]),
  ]);

  expect(function () use ($polygon): void {
    unset($polygon[0]);
  })->toThrow(InvalidArgumentException::class);
});

it('checks if geometry collection item is exists', function (): void {
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

  $firstItemExists = isset($geometryCollection[0]);
  $secondItemExists = isset($geometryCollection[1]);
  $thirdItemExists = isset($geometryCollection[2]);

  expect($firstItemExists)->toBeTrue();
  expect($secondItemExists)->toBeTrue();
  expect($thirdItemExists)->toBeFalse();
});

it('sets item to geometry collection', function (): void {
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

  $geometryCollection[2] = $lineString;

  expect($geometryCollection[2])->toBe($lineString);
});

it('throws exception when setting invalid item to geometry collection', function (): void {
  $polygon = new Polygon([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
      new Point(2, 178),
      new Point(3, 177),
      new Point(0, 180),
    ]),
  ]);

  expect(function () use ($polygon): void {
    // @phpstan-ignore-next-line
    $polygon[1] = new Point(0, 180);
  })->toThrow(InvalidArgumentException::class);
});

it('casts a GeometryCollection to a string', function (): void {
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

  expect($geometryCollection->__toString())->toEqual('GEOMETRYCOLLECTION(POLYGON((180 0, 179 1, 178 2, 177 3, 180 0)), POINT(180 0))');
});

it('adds a macro toGeometryCollection', function (): void {
  Geometry::macro('getName', function (): string {
    /** @var Geometry $this */
    // @phpstan-ignore-next-line
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

  // @phpstan-ignore-next-line
  expect($geometryCollection->getName())->toBe('GeometryCollection');
});
