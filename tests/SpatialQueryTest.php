<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\SpatialQuery;

uses(DatabaseMigrations::class);

it('creates a convex hull', function (): void {
  $points = \MatanYadaev\EloquentSpatial\Objects\MultiPoint::fromJson('{"type":"MultiPoint","coordinates":[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1],[0,0]]}', 0);

  $hull = SpatialQuery::make()
    ->convexHull($points)
    ->first();

  // @phpstan-ignore-next-line
  $returnedPolygon = Polygon::fromWkb($hull->convex_hull)->toArray();

  $expectedPolygon = Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}')->toArray();

  // Compare coordinates like this to prevent ordering differences between database types/versions
  // @phpstan-ignore-next-line
  expect($returnedPolygon['coordinates'][0])->toEqualCanonicalizing($expectedPolygon['coordinates'][0]);
});
