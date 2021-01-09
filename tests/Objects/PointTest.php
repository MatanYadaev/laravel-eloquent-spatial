<?php

namespace MatanYadaev\EloquentSpatial\Tests\Objects;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestCase;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class PointTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_stores_point()
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'point' => new Point(23.1, 55.5),
        ])->fresh();

        $this->assertTrue($testPlace->point instanceof Point);
        $this->assertEquals(23.1, $testPlace->point->latitude);
        $this->assertEquals(55.5, $testPlace->point->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }
}
