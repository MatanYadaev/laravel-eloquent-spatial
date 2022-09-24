<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

uses(DatabaseMigrations::class);

it('creates a model record with polygon', function (): void {
  $polygon = new Polygon([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
      new Point(2, 178),
      new Point(3, 177),
      new Point(0, 180),
    ]),
  ]);

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['polygon' => $polygon]);

  expect($testPlace->polygon)->toBeInstanceOf(Polygon::class);
  expect($testPlace->polygon)->toEqual($polygon);
});

it('creates a model record with polygon with SRID', function (): void {
  $polygon = new Polygon([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
      new Point(2, 178),
      new Point(3, 177),
      new Point(0, 180),
    ]),
  ], 4326);

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['polygon' => $polygon]);

  expect($testPlace->polygon->srid)->toBe(4326);
});

it('creates polygon from JSON', function (): void {
  $polygon = new Polygon([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
      new Point(2, 178),
      new Point(3, 177),
      new Point(0, 180),
    ]),
  ]);

  $polygonFromJson = Polygon::fromJson('{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}');

  expect($polygonFromJson)->toEqual($polygon);
});

it('creates polygon with SRID from JSON', function (): void {
  $polygon = new Polygon([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
      new Point(2, 178),
      new Point(3, 177),
      new Point(0, 180),
    ]),
  ], 4326);

  $polygonFromJson = Polygon::fromJson('{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}', 4326);

  expect($polygonFromJson)->toEqual($polygon);
});

it('generates polygon JSON', function (): void {
  $polygon = new Polygon([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
      new Point(2, 178),
      new Point(3, 177),
      new Point(0, 180),
    ]),
  ]);

  $json = $polygon->toJson();

  $expectedJson = '{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}';
  expect($json)->toBe($expectedJson);
});

it('generates polygon feature collection JSON', function (): void {
  $polygon = new Polygon([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
      new Point(2, 178),
      new Point(3, 177),
      new Point(0, 180),
    ]),
  ]);

  $featureCollectionJson = $polygon->toFeatureCollectionJson();

  $expectedFeatureCollectionJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"Polygon","coordinates":[[[180,0],[179,1],[178,2],[177,3],[180,0]]]}}]}';
  expect($featureCollectionJson)->toBe($expectedFeatureCollectionJson);
});

it('creates polygon from WKT', function (): void {
  $polygon = new Polygon([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
      new Point(2, 178),
      new Point(3, 177),
      new Point(0, 180),
    ]),
  ]);

  $polygonFromWkt = Polygon::fromWkt('POLYGON((180 0, 179 1, 178 2, 177 3, 180 0))');

  expect($polygonFromWkt)->toEqual($polygon);
});

it('creates polygon with SRID from WKT', function (): void {
  $polygon = new Polygon([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
      new Point(2, 178),
      new Point(3, 177),
      new Point(0, 180),
    ]),
  ], 4326);

  $polygonFromWkt = Polygon::fromWkt('POLYGON((180 0, 179 1, 178 2, 177 3, 180 0))', 4326);

  expect($polygonFromWkt)->toEqual($polygon);
});

it('generates polygon WKT', function (): void {
  $polygon = new Polygon([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
      new Point(2, 178),
      new Point(3, 177),
      new Point(0, 180),
    ]),
  ]);

  $wkt = $polygon->toWkt();

  $expectedWkt = 'POLYGON((180 0, 179 1, 178 2, 177 3, 180 0))';
  expect($wkt)->toBe($expectedWkt);
});

it('creates polygon from WKB', function (): void {
  $polygon = new Polygon([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
      new Point(2, 178),
      new Point(3, 177),
      new Point(0, 180),
    ]),
  ]);

  $polygonFromWkb = Polygon::fromWkb($polygon->toWkb());

  expect($polygonFromWkb)->toEqual($polygon);
});

it('creates polygon with SRID from WKB', function (): void {
  $polygon = new Polygon([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
      new Point(2, 178),
      new Point(3, 177),
      new Point(0, 180),
    ]),
  ], 4326);

  $polygonFromWkb = Polygon::fromWkb($polygon->toWkb());

  expect($polygonFromWkb)->toEqual($polygon);
});

it('throws exception when polygon has no line strings', function (): void {
  expect(function (): void {
    new Polygon([]);
  })->toThrow(InvalidArgumentException::class);
});

it('throws exception when creating polygon from incorrect geometry', function (): void {
  expect(function (): void {
    // @phpstan-ignore-next-line
    new Polygon([
      new Point(0, 0),
    ]);
  })->toThrow(InvalidArgumentException::class);
});

it('casts a Polygon to a string', function (): void {
  $polygon = $polygon = new Polygon([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
      new Point(2, 178),
      new Point(3, 177),
      new Point(0, 180),
    ]),
  ]);

  expect($polygon->__toString())->toEqual('POLYGON((180 0, 179 1, 178 2, 177 3, 180 0))');
});
