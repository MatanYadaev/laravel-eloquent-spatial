<?php

namespace MatanYadaev\EloquentSpatial\Tests\Objects;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestCase;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestSridPlace;

class PointTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @environment-setup useMysql
     */
    public function it_stores_point_in_mysql()
    {
        $testPlace = TestPlace::factory()->create([
            'location' => new Point(23.1, 55.5),
        ]);
        $testPlace = TestPlace::find($testPlace->id);

        $this->assertTrue($testPlace->location instanceof Point);
        $this->assertEquals($testPlace->location->latitude, 23.1);
        $this->assertEquals($testPlace->location->longitude, 55.5);
        $this->assertEquals($testPlace->location->srid, 0);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /**
     * @test
     * @environment-setup useMysql
     */
    public function it_stores_srid_point_in_mysql()
    {
        $testSridPlace = TestSridPlace::factory()->create([
            'location' => new Point(23.1, 55.5, 3857),
        ]);
        $testSridPlace = TestSridPlace::find($testSridPlace->id);

        $this->assertTrue($testSridPlace->location instanceof Point);
        $this->assertEquals($testSridPlace->location->latitude, 23.1);
        $this->assertEquals($testSridPlace->location->longitude, 55.5);
        $this->assertEquals($testSridPlace->location->srid, 3857);

        $this->assertDatabaseCount($testSridPlace->getTable(), 1);
    }
}
