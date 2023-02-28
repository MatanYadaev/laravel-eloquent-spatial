<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\GeometryUtilities;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;

uses(DatabaseMigrations::class);

it('creates a convex hull', function (): void {
  $points = \MatanYadaev\EloquentSpatial\Objects\MultiPoint::fromJson('{"type":"MultiPoint","coordinates":[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1],[0,0]]}', 0);

  $hull = GeometryUtilities::make()
    ->convexHull($points);

  $expectedPolygon = Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}')->toArray();

  expect($hull)->toBeInstanceOf(Polygon::class);

  // Compare coordinates like this to prevent ordering differences between database types/versions
  // @phpstan-ignore-next-line
  expect($hull->toArray()['coordinates'][0])->toEqualCanonicalizing($expectedPolygon['coordinates'][0]);
});

it('calculates the distance between points on a sphere', function (): void {

  $point1 = new Point(41.9631174, -87.6770458);
  $point2 = new Point(40.7628267, -73.9898293);

  $distance = GeometryUtilities::make()
    ->distanceSphere($point1, $point2);

  expect($distance)->toBe(1148798.720296128);
});

it('calculates the distance between points on a sphere with sphere size', function (): void {

  $point1 = new Point(41.9631174, -87.6770458);
  $point2 = new Point(40.7628267, -73.9898293);

  $distance = GeometryUtilities::make()
    ->distanceSphere($point1, $point2, 1);

  expect($distance)->toBe(0.18031725706132895);
});
