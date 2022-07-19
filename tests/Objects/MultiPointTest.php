<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

uses(DatabaseMigrations::class);

it('creates a model record with multi point', function (): void {
  $multiPoint = new MultiPoint([
    new Point(180, 0),
  ]);

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['multi_point' => $multiPoint]);

  expect($testPlace->multi_point)->toBeInstanceOf(MultiPoint::class);
  expect($testPlace->multi_point)->toEqual($multiPoint);
});

it('creates a model record with multi point with SRID', function (): void {
  $multiPoint = new MultiPoint([
    new Point(180, 0),
  ], 4326);

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['multi_point' => $multiPoint]);

  expect($testPlace->multi_point->srid)->toBe(4326);
});

it('creates multi point from JSON', function (): void {
  $multiPoint = new MultiPoint([
    new Point(180, 0),
  ]);

  $multiPointFromJson = MultiPoint::fromJson('{"type":"MultiPoint","coordinates":[[0,180]]}');

  expect($multiPointFromJson)->toEqual($multiPoint);
});

it('creates multi point with SRID from JSON', function (): void {
  $multiPoint = new MultiPoint([
    new Point(180, 0),
  ], 4326);

  $multiPointFromJson = MultiPoint::fromJson('{"type":"MultiPoint","coordinates":[[0,180]]}', 4326);

  expect($multiPointFromJson)->toEqual($multiPoint);
});

it('generates multi point JSON', function (): void {
  $multiPoint = new MultiPoint([
    new Point(180, 0),
  ]);

  $json = $multiPoint->toJson();

  $expectedJson = '{"type":"MultiPoint","coordinates":[[0,180]]}';
  expect($json)->toBe($expectedJson);
});

it('generates multi point feature collection JSON', function (): void {
  $multiPoint = new MultiPoint([
    new Point(180, 0),
  ]);

  $multiPointFeatureCollectionJson = $multiPoint->toFeatureCollectionJson();

  $expectedFeatureCollectionJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"MultiPoint","coordinates":[[0,180]]}}]}';
  expect($multiPointFeatureCollectionJson)->toBe($expectedFeatureCollectionJson);
});

it('creates multi point from WKT', function (): void {
  $multiPoint = new MultiPoint([
    new Point(180, 0),
  ]);

  $multiPointFromWkt = MultiPoint::fromWkt('MULTIPOINT(0 180)');

  expect($multiPointFromWkt)->toEqual($multiPoint);
});

it('creates multi point with SRID from WKT', function (): void {
  $multiPoint = new MultiPoint([
    new Point(180, 0),
  ], 4326);

  $multiPointFromWkt = MultiPoint::fromWkt('MULTIPOINT(0 180)', 4326);

  expect($multiPointFromWkt)->toEqual($multiPoint);
});

it('creates multi point from WKB', function (): void {
  $multiPoint = new MultiPoint([
    new Point(180, 0),
  ]);

  $multiPointFromWkb = MultiPoint::fromWkb($multiPoint->toWkb());

  expect($multiPointFromWkb)->toEqual($multiPoint);
});

it('creates multi point with SRID from WKB', function (): void {
  $multiPoint = new MultiPoint([
    new Point(180, 0),
  ], 4326);

  $multiPointFromWkb = MultiPoint::fromWkb($multiPoint->toWkb(), 4326);

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
      Polygon::fromJson('{"type":"Polygon","coordinates":[[[0,180],[1,179],[2,178],[3,177],[0,180]]]}'),
    ]);
  })->toThrow(InvalidArgumentException::class);
});
