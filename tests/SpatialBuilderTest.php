<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

uses(DatabaseMigrations::class);

it('calculates distance between column and column', function (): void {
  TestPlace::factory()->create(['point' => new Point(0, 0)]);

  /** @var TestPlace $testPlaceWithDistance */
  $testPlaceWithDistance = TestPlace::query()
    ->withDistance('point', 'point')
    ->firstOrFail();

  expect($testPlaceWithDistance->distance)->toBe(0.0);
});

it('calculates distance between column and geometry', function (): void {
  TestPlace::factory()->create(['point' => new Point(0, 0)]);

  /** @var TestPlace $testPlaceWithDistance */
  $testPlaceWithDistance = TestPlace::query()
    ->withDistance('point', new Point(1, 1))
    ->firstOrFail();

  expect($testPlaceWithDistance->distance)->toBe(1.4142135623730951);
});

it('calculates distance with alias', function (): void {
  TestPlace::factory()->create(['point' => new Point(0, 0)]);

  /** @var TestPlace $testPlaceWithDistance */
  $testPlaceWithDistance = TestPlace::query()
    ->withDistance('point', new Point(1, 1), 'distance_in_meters')
    ->firstOrFail();

  expect($testPlaceWithDistance->distance_in_meters)->toBe(1.4142135623730951);
});

it('filters by distance', function (): void {
  $pointWithinDistance = new Point(0, 0);
  $pointNotWithinDistance = new Point(50, 50);
  TestPlace::factory()->create(['point' => $pointWithinDistance]);
  TestPlace::factory()->create(['point' => $pointNotWithinDistance]);

  /** @var TestPlace[] $testPlacesWithinDistance */
  $testPlacesWithinDistance = TestPlace::query()
    ->whereDistance('point', new Point(1, 1), '<', 10)
    ->get();

  expect($testPlacesWithinDistance)->toHaveCount(1);
  expect($testPlacesWithinDistance[0]->point)->toEqual($pointWithinDistance);
});

it('orders by distance', function (): void {
  $closerTestPlace = TestPlace::factory()->create(['point' => new Point(1, 1)]);
  $fartherTestPlace = TestPlace::factory()->create(['point' => new Point(2, 2)]);

  /** @var TestPlace[] $testPlacesOrderedByDistance */
  $testPlacesOrderedByDistance = TestPlace::query()
    ->orderByDistance('point', new Point(0, 0))
    ->get();

  expect($testPlacesOrderedByDistance[0]->id)->toBe($closerTestPlace->id);
  expect($testPlacesOrderedByDistance[1]->id)->toBe($fartherTestPlace->id);
});

it('desc orders by distance', function (): void {
  $closerTestPlace = TestPlace::factory()->create(['point' => new Point(1, 1)]);
  $fartherTestPlace = TestPlace::factory()->create(['point' => new Point(2, 2)]);

  /** @var TestPlace[] $testPlacesOrderedByDistance */
  $testPlacesOrderedByDistance = TestPlace::query()
    ->orderByDistance('point', new Point(0, 0), 'desc')
    ->get();

  expect($testPlacesOrderedByDistance[1]->id)->toBe($closerTestPlace->id);
  expect($testPlacesOrderedByDistance[0]->id)->toBe($fartherTestPlace->id);
});

it('calculates distance sphere column and column', function (): void {
  TestPlace::factory()->create(['point' => new Point(0, 0)]);

  /** @var TestPlace $testPlaceWithDistance */
  $testPlaceWithDistance = TestPlace::query()
    ->withDistanceSphere('point', 'point')
    ->firstOrFail();

  expect($testPlaceWithDistance->distance)->toBe(0.0);
});

it('calculates distance sphere column and geometry', function (): void {
  TestPlace::factory()->create(['point' => new Point(0, 0)]);

  /** @var TestPlace $testPlaceWithDistance */
  $testPlaceWithDistance = TestPlace::query()
    ->withDistanceSphere('point', new Point(1, 1))
    ->firstOrFail();

  expect($testPlaceWithDistance->distance)->toBe(157249.0357231545);
});

it('calculates distance sphere with alias', function (): void {
  TestPlace::factory()->create(['point' => new Point(0, 0)]);

  /** @var TestPlace $testPlaceWithDistance */
  $testPlaceWithDistance = TestPlace::query()
    ->withDistanceSphere('point', new Point(1, 1), 'distance_in_meters')
    ->firstOrFail();

  expect($testPlaceWithDistance->distance_in_meters)->toBe(157249.0357231545);
});

it('filters distance sphere', function (): void {
  $pointWithinDistance = new Point(0, 0);
  $pointNotWithinDistance = new Point(50, 50);
  TestPlace::factory()->create(['point' => $pointWithinDistance]);
  TestPlace::factory()->create(['point' => $pointNotWithinDistance]);

  /** @var TestPlace[] $testPlacesWithinDistance */
  $testPlacesWithinDistance = TestPlace::query()
    ->whereDistanceSphere('point', new Point(1, 1), '<', 200000)
    ->get();

  expect($testPlacesWithinDistance)->toHaveCount(1);
  expect($testPlacesWithinDistance[0]->point)->toEqual($pointWithinDistance);
});

it('orders by distance sphere', function (): void {
  $closerTestPlace = TestPlace::factory()->create(['point' => new Point(1, 1)]);
  $fartherTestPlace = TestPlace::factory()->create(['point' => new Point(2, 2)]);

  /** @var TestPlace[] $testPlacesOrderedByDistance */
  $testPlacesOrderedByDistance = TestPlace::query()
    ->orderByDistanceSphere('point', new Point(0, 0))
    ->get();

  expect($testPlacesOrderedByDistance[0]->id)->toBe($closerTestPlace->id);
  expect($testPlacesOrderedByDistance[1]->id)->toBe($fartherTestPlace->id);
});

it('desc orders by distance sphere', function (): void {
  $closerTestPlace = TestPlace::factory()->create(['point' => new Point(1, 1)]);
  $fartherTestPlace = TestPlace::factory()->create(['point' => new Point(2, 2)]);

  /** @var TestPlace[] $testPlacesOrderedByDistance */
  $testPlacesOrderedByDistance = TestPlace::query()
    ->orderByDistanceSphere('point', new Point(0, 0), 'desc')
    ->get();

  expect($testPlacesOrderedByDistance[1]->id)->toBe($closerTestPlace->id);
  expect($testPlacesOrderedByDistance[0]->id)->toBe($fartherTestPlace->id);
});

it('filters by within', function (): void {
  $polygon = Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}');
  $pointWithinPolygon = new Point(0, 0);
  $pointOutsidePolygon = new Point(50, 50);
  TestPlace::factory()->create(['point' => $pointWithinPolygon]);
  TestPlace::factory()->create(['point' => $pointOutsidePolygon]);

  /** @var TestPlace[] $testPlacesWithinPolygon */
  $testPlacesWithinPolygon = TestPlace::query()
    ->whereWithin('point', $polygon)
    ->get();

  expect($testPlacesWithinPolygon)->toHaveCount(1);
  expect($testPlacesWithinPolygon[0]->point)->toEqual($pointWithinPolygon);
});

it('filters by contains', function (): void {
  $polygon = Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}');
  $pointWithinPolygon = new Point(0, 0);
  $pointOutsidePolygon = new Point(50, 50);
  TestPlace::factory()->create(['polygon' => $polygon]);

  $testPlace = TestPlace::query()
    ->whereContains('polygon', $pointWithinPolygon)
    ->first();
  $testPlace2 = TestPlace::query()
    ->whereContains('polygon', $pointOutsidePolygon)
    ->first();

  expect($testPlace)->not->toBeNull();
  expect($testPlace2)->toBeNull();
});

it('filters by touches', function (): void {
  $polygon = Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[0,-1],[0,0],[-1,0],[-1,-1]]]}');
  $pointTouchesPolygon = new Point(0, 0);
  $pointNotTouchesPolygon = new Point(50, 50);
  TestPlace::factory()->create(['point' => $pointTouchesPolygon]);
  TestPlace::factory()->create(['point' => $pointNotTouchesPolygon]);

  /** @var TestPlace[] $testPlacesTouchPolygon */
  $testPlacesTouchPolygon = TestPlace::query()
    ->whereTouches('point', $polygon)
    ->get();

  expect($testPlacesTouchPolygon)->toHaveCount(1);
  expect($testPlacesTouchPolygon[0]->point)->toEqual($pointTouchesPolygon);
});

it('filters by intersects', function (): void {
  $polygon = Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}');
  $pointIntersectsPolygon = new Point(0, 0);
  $pointNotIntersectsPolygon = new Point(50, 50);
  TestPlace::factory()->create(['point' => $pointIntersectsPolygon]);
  TestPlace::factory()->create(['point' => $pointNotIntersectsPolygon]);

  /** @var TestPlace[] $testPlacesInterestPolygon */
  $testPlacesInterestPolygon = TestPlace::query()
    ->whereIntersects('point', $polygon)
    ->get();

  expect($testPlacesInterestPolygon)->toHaveCount(1);
  expect($testPlacesInterestPolygon[0]->point)->toEqual($pointIntersectsPolygon);
});

it('filters by crosses', function (): void {
  $polygon = Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}');
  $lineStringCrossesPolygon = LineString::fromJson('{"type":"LineString","coordinates":[[0,0],[2,0]]}');
  $lineStringNotCrossesPolygon = LineString::fromJson('{"type":"LineString","coordinates":[[50,50],[52,50]]}');
  TestPlace::factory()->create(['line_string' => $lineStringCrossesPolygon]);
  TestPlace::factory()->create(['line_string' => $lineStringNotCrossesPolygon]);

  /** @var TestPlace[] $testPlacesCrossPolygon */
  $testPlacesCrossPolygon = TestPlace::query()
    ->whereCrosses('line_string', $polygon)
    ->get();

  expect($testPlacesCrossPolygon)->toHaveCount(1);
  expect($testPlacesCrossPolygon[0]->line_string)->toEqual($lineStringCrossesPolygon);
});

it('filters by disjoint', function (): void {
  $polygon = Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[-0.5,-1],[-0.5,-0.5],[-1,-0.5],[-1,-1]]]}');
  $pointDisjointsPolygon = new Point(0, 0);
  $pointNotDisjointsPolygon = new Point(-1, -1);
  TestPlace::factory()->create(['point' => $pointDisjointsPolygon]);
  TestPlace::factory()->create(['point' => $pointNotDisjointsPolygon]);

  /** @var TestPlace[] $testPlacesDisjointPolygon */
  $testPlacesDisjointPolygon = TestPlace::query()
    ->whereDisjoint('point', $polygon)
    ->get();

  expect($testPlacesDisjointPolygon)->toHaveCount(1);
  expect($testPlacesDisjointPolygon[0]->point)->toEqual($pointDisjointsPolygon);
});

it('filters by overlaps', function (): void {
  $polygon = Polygon::fromJson('{"type":"Polygon","coordinates":[[[-0.75,-0.75],[1,-1],[1,1],[-1,1],[-0.75,-0.75]]]}');
  $overlappingPolygon = Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[-0.5,-1],[-0.5,-0.5],[-1,-0.5],[-1,-1]]]}');
  $notOverlappingPolygon = Polygon::fromJson('{"type":"Polygon","coordinates":[[[-10,-10],[-5,-10],[-5,-5],[-10,-5],[-10,-10]]]}');
  TestPlace::factory()->create(['polygon' => $overlappingPolygon]);
  TestPlace::factory()->create(['polygon' => $notOverlappingPolygon]);

  /** @var TestPlace[] $overlappingTestPlaces */
  $overlappingTestPlaces = TestPlace::query()
    ->whereOverlaps('polygon', $polygon)
    ->get();

  expect($overlappingTestPlaces)->toHaveCount(1);
  expect($overlappingTestPlaces[0]->polygon)->toEqual($overlappingPolygon);
});

it('filters by equals', function (): void {
  $point1 = new Point(0, 0);
  $point2 = new Point(50, 50);
  TestPlace::factory()->create(['point' => $point1]);
  TestPlace::factory()->create(['point' => $point2]);

  /** @var TestPlace[] $testPlaces */
  $testPlaces = TestPlace::query()
    ->whereEquals('point', $point1)
    ->get();

  expect($testPlaces)->toHaveCount(1);
  expect($testPlaces[0]->point)->toEqual($point1);
});
