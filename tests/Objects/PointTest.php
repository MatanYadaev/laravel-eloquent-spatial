<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Constants\SRID;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

uses(DatabaseMigrations::class);

it('creates a model record with point', function (): void {
  $point = new Point(0, 180);

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['point' => $point]);

  expect($testPlace->point)->toBeInstanceOf(Point::class);
  expect($testPlace->point)->toEqual($point);
});

it('creates a model record with point with SRID', function (): void {
  $point = new Point(0, 180, SRID::GCS->value);

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['point' => $point]);

  expect($testPlace->point->srid)->toBe(SRID::GCS->value);
});

it('creates point from JSON', function (): void {
  $point = new Point(0, 180);

  $pointFromJson = Point::fromJson('{"type":"Point","coordinates":[180,0]}');

  expect($pointFromJson)->toEqual($point);
});

it('creates point with SRID from JSON', function (): void {
  $point = new Point(0, 180, SRID::GCS->value);

  $pointFromJson = Point::fromJson('{"type":"Point","coordinates":[180,0]}', SRID::GCS->value);

  expect($pointFromJson)->toEqual($point);
});

it('generates point JSON', function (): void {
  $point = new Point(0, 180);

  $json = $point->toJson();

  $expectedJson = '{"type":"Point","coordinates":[180,0]}';
  expect($json)->toBe($expectedJson);
});

it('throws exception when creating point from invalid JSON', function (): void {
  expect(function (): void {
    Point::fromJson('{"type":"Point","coordinates":[]}');
  })->toThrow(InvalidArgumentException::class);
});

it('creates point from WKT', function (): void {
  $point = new Point(0, 180);

  $pointFromWkt = Point::fromWkt('POINT(180 0)');

  expect($pointFromWkt)->toEqual($point);
});

it('creates point with SRID from WKT', function (): void {
  $point = new Point(0, 180, SRID::GCS->value);

  $pointFromWkt = Point::fromWkt('POINT(180 0)', SRID::GCS->value);

  expect($pointFromWkt)->toEqual($point);
});

it('generates point WKT', function (): void {
  $point = new Point(0, 180);

  $wkt = $point->toWkt();

  $expectedWkt = 'POINT(180 0)';
  expect($wkt)->toBe($expectedWkt);
});

it('creates point from WKB', function (): void {
  $point = new Point(0, 180);

  $pointFromWkb = Point::fromWkb($point->toWkb());

  expect($pointFromWkb)->toEqual($point);
});

it('creates point with SRID from WKB', function (): void {
  $point = new Point(0, 180, SRID::GCS->value);

  $pointFromWkb = Point::fromWkb($point->toWkb());

  expect($pointFromWkb)->toEqual($point);
});

it('casts a Point to a string', function (): void {
  $point = new Point(0, 180, SRID::GCS->value);

  expect($point->__toString())->toEqual('POINT(180 0)');
});

it('adds a macro toPoint', function (): void {
  Geometry::macro('getName', function (): string {
    /** @var Geometry $this */
    // @phpstan-ignore-next-line
    return class_basename($this);
  });

  $point = new Point(0, 180, SRID::GCS->value);

  // @phpstan-ignore-next-line
  expect($point->getName())->toBe('Point');
});
