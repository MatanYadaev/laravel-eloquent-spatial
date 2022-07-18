<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

uses(DatabaseMigrations::class);

it('creates a model record with line string', function (): void {
  $lineString = new LineString([
    new Point(180, 0),
    new Point(179, 1),
  ]);

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['line_string' => $lineString]);

  expect($testPlace->line_string)->toBeInstanceOf(LineString::class);
  expect($testPlace->line_string)->toEqual($lineString);
});

it('creates line string from JSON', function (): void {
  $lineString = new LineString([
    new Point(180, 0),
    new Point(179, 1),
  ]);

  $lineStringFromJson = LineString::fromJson('{"type":"LineString","coordinates":[[0,180],[1,179]]}');

  expect($lineStringFromJson)->toEqual($lineString);
});

it('generates line string JSON', function (): void {
  $lineString = new LineString([
    new Point(180, 0),
    new Point(179, 1),
  ]);

  $expectedJson = '{"type":"LineString","coordinates":[[0,180],[1,179]]}';
  expect($lineString->toJson())->toBe($expectedJson);
});

it('generates line string feature collection JSON', function (): void {
  $lineString = new LineString([
    new Point(180, 0),
    new Point(179, 1),
  ]);

  $expectedFeatureCollectionJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"LineString","coordinates":[[0,180],[1,179]]}}]}';
  expect($lineString->toFeatureCollectionJson())->toBe($expectedFeatureCollectionJson);
});

it('throws exception when line string has less than two points', function (): void {
  expect(function (): void {
    new LineString([
      new Point(180, 0),
    ]);
  })->toThrow(InvalidArgumentException::class);
});

it('throws exception when creating line string from incorrect geometry', function (): void {
  expect(function (): void {
    // @phpstan-ignore-next-line
    new LineString([
      Polygon::fromJson('{"type":"Polygon","coordinates":[[[0,180],[1,179],[2,178],[3,177],[0,180]]]}'),
    ]);
  })->toThrow(InvalidArgumentException::class);
});
