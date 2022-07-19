<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
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
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
      ]),
    ]),
    new Point(180, 0),
  ]);

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['geometry_collection' => $geometryCollection]);

  expect($testPlace->geometry_collection)->toBeInstanceOf(GeometryCollection::class);
  expect($testPlace->geometry_collection)->toEqual($geometryCollection);
});

it('creates a model record with geometry collection with SRID', function (): void {
  $geometryCollection = new GeometryCollection([
    new Polygon([
      new LineString([
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
      ]),
    ]),
    new Point(180, 0),
  ], 4326);

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['geometry_collection' => $geometryCollection]);

  expect($testPlace->geometry_collection->srid)->toBe(4326);
});

it('creates geometry collection from JSON', function (): void {
  $geometryCollection = new GeometryCollection([
    new Polygon([
      new LineString([
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
      ]),
    ]),
    new Point(180, 0),
  ]);

  $geometryCollectionFromJson = GeometryCollection::fromJson('{"type":"GeometryCollection","geometries":[{"type":"Polygon","coordinates":[[[0,180],[1,179],[2,178],[3,177],[0,180]]]},{"type":"Point","coordinates":[0,180]}]}');

  expect($geometryCollectionFromJson)->toEqual($geometryCollection);
});

it('creates geometry collection with SRID from JSON', function (): void {
  $geometryCollection = new GeometryCollection([
    new Polygon([
      new LineString([
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
      ]),
    ]),
    new Point(180, 0),
  ], 4326);

  $geometryCollectionFromJson = GeometryCollection::fromJson('{"type":"GeometryCollection","geometries":[{"type":"Polygon","coordinates":[[[0,180],[1,179],[2,178],[3,177],[0,180]]]},{"type":"Point","coordinates":[0,180]}]}', 4326);

  expect($geometryCollectionFromJson)->toEqual($geometryCollection);
});

it('creates geometry collection from feature collection JSON', function (): void {
  $geometryCollection = new GeometryCollection([
    new Polygon([
      new LineString([
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
      ]),
    ]),
    new Point(180, 0),
  ]);

  $geometryCollectionFromFeatureCollectionJson = GeometryCollection::fromJson('{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"Polygon","coordinates":[[[0,180],[1,179],[2,178],[3,177],[0,180]]]}},{"type":"Feature","properties":[],"geometry":{"type":"Point","coordinates":[0,180]}}]}');

  expect($geometryCollectionFromFeatureCollectionJson)->toEqual($geometryCollection);
});

it('generates geometry collection JSON', function (): void {
  $geometryCollection = new GeometryCollection([
    new Polygon([
      new LineString([
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
      ]),
    ]),
    new Point(180, 0),
  ]);

  $json = $geometryCollection->toJson();

  $expectedJson = '{"type":"GeometryCollection","geometries":[{"type":"Polygon","coordinates":[[[0,180],[1,179],[2,178],[3,177],[0,180]]]},{"type":"Point","coordinates":[0,180]}]}';
  expect($json)->toBe($expectedJson);
});

it('generates geometry collection feature collection JSON', function (): void {
  $geometryCollection = new GeometryCollection([
    new Polygon([
      new LineString([
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
      ]),
    ]),
    new Point(180, 0),
  ]);

  $featureCollectionJson = $geometryCollection->toFeatureCollectionJson();

  $expectedFeatureCollectionJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"Polygon","coordinates":[[[0,180],[1,179],[2,178],[3,177],[0,180]]]}},{"type":"Feature","properties":[],"geometry":{"type":"Point","coordinates":[0,180]}}]}';
  expect($featureCollectionJson)->toBe($expectedFeatureCollectionJson);
});

it('creates geometry collection from WKT', function (): void {
  $geometryCollection = new GeometryCollection([
    new Polygon([
      new LineString([
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
      ]),
    ]),
    new Point(180, 0),
  ]);

  $geometryCollectionFromWkt = GeometryCollection::fromWkt('GEOMETRYCOLLECTION(POLYGON((0 180,1 179,2 178,3 177,0 180)),POINT(0 180))');

  expect($geometryCollectionFromWkt)->toEqual($geometryCollection);
});

it('creates geometry collection with SRID from WKT', function (): void {
  $geometryCollection = new GeometryCollection([
    new Polygon([
      new LineString([
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
      ]),
    ]),
    new Point(180, 0),
  ], 4326);

  $geometryCollectionFromWkt = GeometryCollection::fromWkt('GEOMETRYCOLLECTION(POLYGON((0 180,1 179,2 178,3 177,0 180)),POINT(0 180))', 4326);

  expect($geometryCollectionFromWkt)->toEqual($geometryCollection);
});

it('creates geometry collection from WKB', function (): void {
  $geometryCollection = new GeometryCollection([
    new Polygon([
      new LineString([
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
      ]),
    ]),
    new Point(180, 0),
  ]);

  $geometryCollectionFromWkb = GeometryCollection::fromWkb($geometryCollection->toWkb());

  expect($geometryCollectionFromWkb)->toEqual($geometryCollection);
});

it('creates geometry collection with SRID from WKB', function (): void {
  $geometryCollection = new GeometryCollection([
    new Polygon([
      new LineString([
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
      ]),
    ]),
    new Point(180, 0),
  ], 4326);

  $geometryCollectionFromWkb = GeometryCollection::fromWkb($geometryCollection->toWkb(), 4326);

  expect($geometryCollectionFromWkb)->toEqual($geometryCollection);
});

it('does not throw exception when geometry collection has no geometries', function (): void {
  $geometryCollection = new GeometryCollection([]);

  expect($geometryCollection->getGeometries())->toHaveCount(0);
});

it('unsets geometry collection item', function (): void {
  $point = new Point(180, 0);
  $geometryCollection = new GeometryCollection([
    new Polygon([
      new LineString([
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
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
      new Point(180, 0),
      new Point(179, 1),
      new Point(178, 2),
      new Point(177, 3),
      new Point(180, 0),
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
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
      ]),
    ]),
    new Point(180, 0),
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
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
      ]),
    ]),
    new Point(180, 0),
  ]);
  $lineString = new LineString([
    new Point(180, 0),
    new Point(179, 1),
  ]);

  $geometryCollection[2] = $lineString;

  expect($geometryCollection[2])->toBe($lineString);
});

it('throws exception when setting invalid item to geometry collection', function (): void {
  $polygon = new Polygon([
    new LineString([
      new Point(180, 0),
      new Point(179, 1),
      new Point(178, 2),
      new Point(177, 3),
      new Point(180, 0),
    ]),
  ]);

  expect(function () use ($polygon): void {
    // @phpstan-ignore-next-line
    $polygon[1] = new Point(180, 0);
  })->toThrow(InvalidArgumentException::class);
});
