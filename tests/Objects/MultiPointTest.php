<?php

namespace MatanYadaev\EloquentSpatial\Tests\Objects;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestCase;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class MultiPointTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_stores_multi_point()
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'multi_point' => new MultiPoint([
                new Point(23.1, 55.5),
            ]),
        ])->fresh();

        $this->assertTrue($testPlace->multi_point instanceof MultiPoint);

        $points = $testPlace->multi_point->getGeometries();

        $this->assertEquals(23.1, $points[0]->latitude);
        $this->assertEquals(55.5, $points[0]->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_stores_multi_point_from_json()
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'multi_point' => MultiPoint::fromJson('{"type":"MultiPoint","coordinates":[[55.5,23.1]]}'),
        ])->fresh();

        $this->assertTrue($testPlace->multi_point instanceof MultiPoint);

        $points = $testPlace->multi_point->getGeometries();

        $this->assertEquals(23.1, $points[0]->latitude);
        $this->assertEquals(55.5, $points[0]->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_generates_multi_point_geo_json()
    {
        $multiPoint = new MultiPoint([
            new Point(23.1, 55.5),
        ]);

        $this->assertEquals('{"type":"MultiPoint","coordinates":[[55.5,23.1]]}', $multiPoint->toJson());
    }
}
