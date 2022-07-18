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
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
      ]),
    ]),
  ]);

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['multi_polygon' => $multiPolygon]);

  expect($testPlace->multi_polygon)->toBeInstanceOf(MultiPolygon::class);
  expect($testPlace->multi_polygon)->toEqual($multiPolygon);
});

it('creates multi polygon from JSON', function (): void {
  $multiPolygon = new MultiPolygon([
    new Polygon([
      new LineString([
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
      ]),
    ]),
  ]);

  $multiPolygonFromJson = MultiPolygon::fromJson('{"type":"MultiPolygon","coordinates":[[[[0,180],[1,179],[2,178],[3,177],[0,180]]]]}');

  expect($multiPolygonFromJson)->toEqual($multiPolygon);
});

it('generates multi polygon JSON', function (): void {
  $multiPolygon = new MultiPolygon([
    new Polygon([
      new LineString([
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
      ]),
    ]),
  ]);

  $json = $multiPolygon->toJson();

  $expectedJson = '{"type":"MultiPolygon","coordinates":[[[[0,180],[1,179],[2,178],[3,177],[0,180]]]]}';
  expect($json)->toBe($expectedJson);
});

it('generates multi polygon feature collection JSON', function (): void {
  $multiPolygon = new MultiPolygon([
    new Polygon([
      new LineString([
        new Point(180, 0),
        new Point(179, 1),
        new Point(178, 2),
        new Point(177, 3),
        new Point(180, 0),
      ]),
    ]),
  ]);

  $featureCollectionJson = $multiPolygon->toFeatureCollectionJson();

  $expectedFeatureCollectionJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"MultiPolygon","coordinates":[[[[0,180],[1,179],[2,178],[3,177],[0,180]]]]}}]}';
  expect($featureCollectionJson)->toBe($expectedFeatureCollectionJson);
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
