<?php

namespace MatanYadaev\EloquentSpatial\Tests\Objects;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use InvalidArgumentException;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestCase;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class MultiLineStringTest extends TestCase
{
  use DatabaseMigrations;

  /** @test */
  public function it_stores_multi_line_string(): void
  {
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create([
      'multi_line_string' => new MultiLineString([
        new LineString([
          new Point(180, 0),
          new Point(179, 1),
        ]),
      ]),
    ]);

    $this->assertTrue($testPlace->multi_line_string instanceof MultiLineString);

    $lineString = $testPlace->multi_line_string[0];

    $this->assertEquals(180, $lineString[0]->latitude);
    $this->assertEquals(0, $lineString[0]->longitude);
    $this->assertEquals(179, $lineString[1]->latitude);
    $this->assertEquals(1, $lineString[1]->longitude);

    $this->assertDatabaseCount($testPlace->getTable(), 1);
  }

  /** @test */
  public function it_stores_multi_line_string_from_geo_json(): void
  {
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create([
      'multi_line_string' => MultiLineString::fromJson('{"type":"MultiLineString","coordinates":[[[0,180],[1,179]]]}'),
    ]);

    $this->assertTrue($testPlace->multi_line_string instanceof MultiLineString);

    $lineString = $testPlace->multi_line_string[0];

    $this->assertEquals(180, $lineString[0]->latitude);
    $this->assertEquals(0, $lineString[0]->longitude);
    $this->assertEquals(179, $lineString[1]->latitude);
    $this->assertEquals(1, $lineString[1]->longitude);

    $this->assertDatabaseCount($testPlace->getTable(), 1);
  }

  /** @test */
  public function it_generates_multi_line_string_geo_json(): void
  {
    $multiLineString = new MultiLineString([
      new LineString([
        new Point(180, 0),
        new Point(179, 1),
      ]),
    ]);

    $this->assertEquals('{"type":"MultiLineString","coordinates":[[[0,180],[1,179]]]}', $multiLineString->toJson());
  }

  /** @test */
  public function it_generates_multi_line_string_feature_collection_json(): void
  {
    $multiLineString = new MultiLineString([
      new LineString([
        new Point(180, 0),
        new Point(179, 1),
      ]),
    ]);

    $this->assertEquals('{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"MultiLineString","coordinates":[[[0,180],[1,179]]]}}]}', $multiLineString->toFeatureCollectionJson());
  }

  /** @test */
  public function it_throws_exception_when_multi_line_string_has_0_line_strings(): void
  {
    $this->expectException(InvalidArgumentException::class);

    new MultiLineString([]);
  }

  /** @test */
  public function it_throws_exception_when_multi_line_string_has_composed_by_point(): void
  {
    $this->expectException(InvalidArgumentException::class);

    // @phpstan-ignore-next-line
    new MultiLineString([
      new Point(0, 0),
    ]);
  }
}
