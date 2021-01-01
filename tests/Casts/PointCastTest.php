<?php

namespace MatanYadaev\EloquentSpatial\Tests\Casts;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Point;
use MatanYadaev\EloquentSpatial\Tests\TestCase;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class PointCastTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @environment-setup useMysql
     */
    public function it_stores_point_in_mysql()
    {
        $testPlace = TestPlace::factory()->create([
            'location' => new Point(123.1, 55.5),
        ]);
        $testPlace = TestPlace::find($testPlace->id);

        $this->assertTrue($testPlace->location instanceof Point);
        $this->assertEquals($testPlace->location->latitude, 123.1);
        $this->assertEquals($testPlace->location->longitude, 55.5);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }
}
