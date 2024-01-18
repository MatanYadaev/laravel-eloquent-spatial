<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

uses(DatabaseMigrations::class);

it('creates a model record with multi line string', function (): void {
  $multiLineString = new MultiLineString([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
    ]),
  ]);

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['multi_line_string' => $multiLineString]);

  expect($testPlace->multi_line_string)->toBeInstanceOf(MultiLineString::class);
  expect($testPlace->multi_line_string)->toEqual($multiLineString);
});

it('creates a model record with multi line string with SRID integer', function (): void {
  $multiLineString = new MultiLineString([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
    ]),
  ], Srid::WGS84->value);

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['multi_line_string' => $multiLineString]);

  expect($testPlace->multi_line_string->srid)->toBe(Srid::WGS84->value);
});

it('creates a model record with multi line string with SRID enum', function (): void {
  $multiLineString = new MultiLineString([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
    ]),
  ], Srid::WGS84);

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['multi_line_string' => $multiLineString]);

  expect($testPlace->multi_line_string->srid)->toBe(Srid::WGS84->value);
});

it('creates multi line string from JSON', function (): void {
  $multiLineString = new MultiLineString([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
    ]),
  ]);

  $multiLineStringFromJson = MultiLineString::fromJson('{"type":"MultiLineString","coordinates":[[[180,0],[179,1]]]}');

  expect($multiLineStringFromJson)->toEqual($multiLineString);
});

it('creates multi line string with SRID from JSON', function (): void {
  $multiLineString = new MultiLineString([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
    ]),
  ], Srid::WGS84->value);

  $multiLineStringFromJson = MultiLineString::fromJson('{"type":"MultiLineString","coordinates":[[[180,0],[179,1]]]}', Srid::WGS84->value);

  expect($multiLineStringFromJson)->toEqual($multiLineString);
});

it('generates multi line string JSON', function (): void {
  $multiLineString = new MultiLineString([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
    ]),
  ]);

  $json = $multiLineString->toJson();

  $expectedJson = '{"type":"MultiLineString","coordinates":[[[180,0],[179,1]]]}';
  expect($json)->toBe($expectedJson);
});

it('generates multi line string feature collection JSON', function (): void {
  $multiLineString = new MultiLineString([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
    ]),
  ]);

  $featureCollectionJson = $multiLineString->toFeatureCollectionJson();

  $expectedFeatureCollectionJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"MultiLineString","coordinates":[[[180,0],[179,1]]]}}]}';
  expect($featureCollectionJson)->toBe($expectedFeatureCollectionJson);
});

it('creates multi line string from WKT', function (): void {
  $multiLineString = new MultiLineString([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
    ]),
  ]);

  $multiLineStringFromWkt = MultiLineString::fromWkt('MULTILINESTRING((180 0, 179 1))');

  expect($multiLineStringFromWkt)->toEqual($multiLineString);
});

it('creates multi line string with SRID from WKT', function (): void {
  $multiLineString = new MultiLineString([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
    ]),
  ], Srid::WGS84->value);

  $multiLineStringFromWkt = MultiLineString::fromWkt('MULTILINESTRING((180 0, 179 1))', Srid::WGS84->value);

  expect($multiLineStringFromWkt)->toEqual($multiLineString);
});

it('generates multi line string WKT', function (): void {
  $multiLineString = new MultiLineString([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
    ]),
  ]);

  $wkt = $multiLineString->toWkt();

  $expectedWkt = 'MULTILINESTRING((180 0, 179 1))';
  expect($wkt)->toBe($expectedWkt);
});

it('creates multi line string from WKB', function (): void {
  $multiLineString = new MultiLineString([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
    ]),
  ]);

  $multiLineStringFromWkb = MultiLineString::fromWkb($multiLineString->toWkb());

  expect($multiLineStringFromWkb)->toEqual($multiLineString);
});

it('creates multi line string with SRID from WKB', function (): void {
  $multiLineString = new MultiLineString([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
    ]),
  ], Srid::WGS84->value);

  $multiLineStringFromWkb = MultiLineString::fromWkb($multiLineString->toWkb());

  expect($multiLineStringFromWkb)->toEqual($multiLineString);
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

it('casts a MultiLineString to a string', function (): void {
  $multiLineString = new MultiLineString([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
    ]),
  ]);

  expect($multiLineString->__toString())->toEqual('MULTILINESTRING((180 0, 179 1))');
});

it('adds a macro toMultiLineString', function (): void {
  Geometry::macro('getName', function (): string {
    /** @var Geometry $this */
    // @phpstan-ignore-next-line
    return class_basename($this);
  });

  $multiLineString = new MultiLineString([
    new LineString([
      new Point(0, 180),
      new Point(1, 179),
    ]),
  ]);

  // @phpstan-ignore-next-line
  expect($multiLineString->getName())->toBe('MultiLineString');
});
