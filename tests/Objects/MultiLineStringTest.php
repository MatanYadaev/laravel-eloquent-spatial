<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

uses(DatabaseMigrations::class);

it('creates a model record with multi line string', function (): void {
  $multiLineString = new MultiLineString([
    new LineString([
      new Point(180, 0),
      new Point(179, 1),
    ]),
  ]);

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['multi_line_string' => $multiLineString]);

  expect($testPlace->multi_line_string)->toBeInstanceOf(MultiLineString::class);
  expect($testPlace->multi_line_string)->toEqual($multiLineString);
});

it('creates multi line string from JSON', function (): void {
  $multiLineString = new MultiLineString([
    new LineString([
      new Point(180, 0),
      new Point(179, 1),
    ]),
  ]);

  $multiLineStringFromJson = MultiLineString::fromJson('{"type":"MultiLineString","coordinates":[[[0,180],[1,179]]]}');

  expect($multiLineStringFromJson)->toEqual($multiLineString);
});

it('generates multi line string JSON', function (): void {
  $multiLineString = new MultiLineString([
    new LineString([
      new Point(180, 0),
      new Point(179, 1),
    ]),
  ]);

  $json = $multiLineString->toJson();

  $expectedJson = '{"type":"MultiLineString","coordinates":[[[0,180],[1,179]]]}';
  expect($json)->toBe($expectedJson);
});

it('generates multi line string feature collection JSON', function (): void {
  $multiLineString = new MultiLineString([
    new LineString([
      new Point(180, 0),
      new Point(179, 1),
    ]),
  ]);

  $featureCollectionJson = $multiLineString->toFeatureCollectionJson();

  $expectedFeatureCollectionJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"MultiLineString","coordinates":[[[0,180],[1,179]]]}}]}';
  expect($featureCollectionJson)->toBe($expectedFeatureCollectionJson);
});

it('throws exception when multi line string has no line strings', function (): void {
  expect(function (): void {
    new MultiLineString([]);
  })->toThrow(InvalidArgumentException::class);
});

it('throws exception when creating multi line string from incorrect geometry', function (): void {
  expect(function (): void {
    // @phpstan-ignore-next-line
    new MultiLineString([
      new Point(0, 0),
    ]);
  })->toThrow(InvalidArgumentException::class);
});
