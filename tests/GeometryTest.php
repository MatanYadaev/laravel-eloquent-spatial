<?php


use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;

uses(DatabaseMigrations::class);


it('throws exception when generating geometry from other geometry WKB', function (): void {
  expect(function (): void {
    $pointWkb = (new Point(0, 180))->toWkb();

    LineString::fromWkb($pointWkb);
  })->toThrow(InvalidArgumentException::class);
});

it('throws exception when generating geometry from other geometry WKT', function (): void {
  expect(function (): void {
    $pointWkt = 'POINT(0 180)';

    LineString::fromWkt($pointWkt);
  })->toThrow(InvalidArgumentException::class);
});

it('throws exception when generating geometry from non-JSON', function (): void {
  expect(function (): void {
    Point::fromJson('invalid-value');
  })->toThrow(InvalidArgumentException::class);
});

it('throws exception when generating geometry from empty JSON', function (): void {
  expect(function (): void {
    Point::fromJson('{}');
  })->toThrow(InvalidArgumentException::class);
});

it('throws exception when generating geometry from other geometry JSON', function (): void {
  expect(function (): void {
    $pointJson = '{"type":"Point","coordinates":[0,180]}';

    LineString::fromJson($pointJson);
  })->toThrow(InvalidArgumentException::class);
});
