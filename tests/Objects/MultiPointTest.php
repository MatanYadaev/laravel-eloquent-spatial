<?php


use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
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

it('creates multi point from JSON', function (): void {
  $multiPoint = new MultiPoint([
    new Point(180, 0),
  ]);

  $multiPointFromJson = MultiPoint::fromJson('{"type":"MultiPoint","coordinates":[[0,180]]}');

  expect($multiPointFromJson)->toEqual($multiPoint);
});

it('generates multi point geo json', function (): void {
  $multiPoint = new MultiPoint([
    new Point(180, 0),
  ]);

  $expectedJson = '{"type":"MultiPoint","coordinates":[[0,180]]}';
  expect($multiPoint->toJson())->toBe($expectedJson);
});

it('generates multi point feature collection JSON', function (): void {
  $multiPoint = new MultiPoint([
    new Point(180, 0),
  ]);

  $multiPointFeatureCollectionJson = $multiPoint->toFeatureCollectionJson();

  $expectedFeatureCollectionJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"MultiPoint","coordinates":[[0,180]]}}]}';
  expect($multiPointFeatureCollectionJson)->toBe($expectedFeatureCollectionJson);
});

it('throws exception when multi point has no points', function (): void {
  expect(function (): void {
    new MultiPoint([]);
  })->toThrow(InvalidArgumentException::class);
});

it('throws exception when creating multi point from invalid geometry', function (): void {
  expect(function (): void {
    // @phpstan-ignore-next-line
    new MultiPoint([
      Polygon::fromJson('{"type":"Polygon","coordinates":[[[0,180],[1,179],[2,178],[3,177],[0,180]]]}'),
    ]);
  })->toThrow(InvalidArgumentException::class);
});
