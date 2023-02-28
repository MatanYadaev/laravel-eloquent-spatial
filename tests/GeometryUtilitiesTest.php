<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\GeometryUtilities;
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
