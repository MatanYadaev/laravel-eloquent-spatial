<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

uses(DatabaseMigrations::class);

it('creates a model record with multi polygon', function (): void {
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

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['multi_polygon' => $multiPolygon]);

  expect($testPlace->multi_polygon)->toBeInstanceOf(MultiPolygon::class);
  expect($testPlace->multi_polygon)->toEqual($multiPolygon);
});

it('creates a model record with multi polygon with SRID', function (): void {
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

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['multi_polygon' => $multiPolygon]);

  expect($testPlace->multi_polygon->srid)->toBe(4326);
});

it('creates multi polygon from JSON', function (): void {
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

  $multiPolygonFromJson = MultiPolygon::fromJson('{"type":"MultiPolygon","coordinates":[[[[180,0],[179,1],[178,2],[177,3],[180,0]]]]}');

  expect($multiPolygonFromJson)->toEqual($multiPolygon);
});

it('creates multi polygon with SRID from JSON', function (): void {
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

  $multiPolygonFromJson = MultiPolygon::fromJson('{"type":"MultiPolygon","coordinates":[[[[180,0],[179,1],[178,2],[177,3],[180,0]]]]}', 4326);

  expect($multiPolygonFromJson)->toEqual($multiPolygon);
});

it('generates multi polygon JSON', function (): void {
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

  $json = $multiPolygon->toJson();

  $expectedJson = '{"type":"MultiPolygon","coordinates":[[[[180,0],[179,1],[178,2],[177,3],[180,0]]]]}';
  expect($json)->toBe($expectedJson);
});

it('generates multi polygon feature collection JSON', function (): void {
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

  $featureCollectionJson = $multiPolygon->toFeatureCollectionJson();

  $expectedFeatureCollectionJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"MultiPolygon","coordinates":[[[[180,0],[179,1],[178,2],[177,3],[180,0]]]]}}]}';
  expect($featureCollectionJson)->toBe($expectedFeatureCollectionJson);
});

it('creates multi polygon from WKT', function (): void {
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

  $multiPolygonFromWkt = MultiPolygon::fromWkt('MULTIPOLYGON(((180 0, 179 1, 178 2, 177 3, 180 0)))');

  expect($multiPolygonFromWkt)->toEqual($multiPolygon);
});

it('creates multi polygon with SRID from WKT', function (): void {
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

  $multiPolygonFromWkt = MultiPolygon::fromWkt('MULTIPOLYGON(((180 0, 179 1, 178 2, 177 3, 180 0)))', 4326);

  expect($multiPolygonFromWkt)->toEqual($multiPolygon);
});

it('generates multi polygon WKT', function (): void {
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

  $wkt = $multiPolygon->toWkt();

  $expectedWkt = 'MULTIPOLYGON(((180 0, 179 1, 178 2, 177 3, 180 0)))';
  expect($wkt)->toBe($expectedWkt);
});

it('creates multi polygon from WKB', function (): void {
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

  $multiPolygonFromWkb = MultiPolygon::fromWkb($multiPolygon->toWkb());

  expect($multiPolygonFromWkb)->toEqual($multiPolygon);
});

it('creates multi polygon with SRID from WKB', function (): void {
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

  $multiPolygonFromWkb = MultiPolygon::fromWkb($multiPolygon->toWkb());

  expect($multiPolygonFromWkb)->toEqual($multiPolygon);
});

it('throws exception when multi polygon has no polygons', function (): void {
  expect(function (): void {
    new MultiPolygon([]);
  })->toThrow(InvalidArgumentException::class);
});

it('throws exception when creating multi polygon from incorrect geometry', function (): void {
  expect(function (): void {
    // @phpstan-ignore-next-line
    new MultiPolygon([
      new Point(0, 0),
    ]);
  })->toThrow(InvalidArgumentException::class);
});

it('casts a MultiPolygon to a string', function (): void {
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

  expect($multiPolygon->__toString())->toEqual('MULTIPOLYGON(((180 0, 179 1, 178 2, 177 3, 180 0)))');
});
