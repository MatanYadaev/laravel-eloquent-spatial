<?php

namespace MatanYadaev\EloquentSpatial\Tests\Objects;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestCase;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class LineStringTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_stores_line_string()
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'line_string' => new LineString([
                new Point(23.1, 55.5),
                new Point(23.2, 55.6),
            ]),
        ])->fresh();

        $this->assertTrue($testPlace->line_string instanceof LineString);

        $points = $testPlace->line_string->getGeometries();

        $this->assertEquals(23.1, $points[0]->latitude);
        $this->assertEquals(55.5, $points[0]->longitude);
        $this->assertEquals(0, $points[0]->srid);
        $this->assertEquals(23.2, $points[1]->latitude);
        $this->assertEquals(55.6, $points[1]->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }
}
