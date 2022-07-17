<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

uses(DatabaseMigrations::class);

it('creates a model record with point', function (): void {
  $point = new Point(180, 0);

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['point' => $point]);

  expect($testPlace->point)->toBeInstanceOf(Point::class);
  expect($testPlace->point)->toEqual($point);
  expect($testPlace->count())->toBe(1);
});

it('creates point from JSON', function (): void {
  $point = new Point(180, 0);

  $pointFromJson = Point::fromJson('{"type":"Point","coordinates":[0,180]}');

  expect($pointFromJson)->toEqual($point);
});

it('generates point json', function (): void {
  $point = new Point(180, 0);

  expect($point->toJson())->toBe('{"type":"Point","coordinates":[0,180]}');
});

it('throws exception when creating point from invalid JSON', function (): void {
  $this->expectException(InvalidArgumentException::class);

  Point::fromJson('{"type":"Point","coordinates":[]}');
});
