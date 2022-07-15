<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

uses(DatabaseMigrations::class);

it('creates a model record with point', function (): void {
  $point = new Point(180, 0);

  /** @var TestPlace $testPlace */
  $testPlace = TestPlace::factory()->create(['point' => $point]);

  $this->assertTrue($testPlace->point instanceof Point);
  $this->assertEquals($point, $testPlace->point);
  $this->assertDatabaseCount($testPlace->getTable(), 1);
});

it('creates point from JSON', function (): void {
  $point = new Point(180, 0);

  $pointFromJson = Point::fromJson('{"type":"Point","coordinates":[0,180]}');

  $this->assertEquals($point, $pointFromJson);
});

it('generates point json', function (): void {
  $point = new Point(180, 0);

  $this->assertEquals('{"type":"Point","coordinates":[0,180]}', $point->toJson());
});

it('throws exception when creating point from invalid JSON', function (): void {
  $this->expectException(InvalidArgumentException::class);

  Point::fromJson('{"type":"Point","coordinates":[]}');
});
