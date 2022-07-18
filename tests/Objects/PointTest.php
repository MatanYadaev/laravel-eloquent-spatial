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
});

it('creates point from JSON', function (): void {
  $point = new Point(180, 0);

  $pointFromJson = Point::fromJson('{"type":"Point","coordinates":[0,180]}');

  expect($pointFromJson)->toEqual($point);
});

it('generates point json', function (): void {
  $point = new Point(180, 0);

  $expectedJson = '{"type":"Point","coordinates":[0,180]}';
  expect($point->toJson())->toBe($expectedJson);
});

it('throws exception when creating point from invalid JSON', function (): void {
  expect(function (): void {
    Point::fromJson('{"type":"Point","coordinates":[]}');
  })->toThrow(InvalidArgumentException::class);
});
