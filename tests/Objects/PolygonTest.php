<?php

namespace MatanYadaev\EloquentSpatial\Tests\Objects;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestCase;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class PolygonTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_stores_polygon()
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'polygon' => new Polygon([
                new LineString([
                    new Point(23.1, 55.5),
                    new Point(23.2, 55.6),
                    new Point(23.3, 55.7),
                    new Point(23.1, 55.5),
                ]),
            ]),
        ])->fresh();

        $this->assertTrue($testPlace->polygon instanceof Polygon);

        $lineStrings = $testPlace->polygon->getGeometries();
        $points = $lineStrings[0]->getGeometries();

        $this->assertEquals(23.1, $points[0]->latitude);
        $this->assertEquals(55.5, $points[0]->longitude);
        $this->assertEquals(23.2, $points[1]->latitude);
        $this->assertEquals(55.6, $points[1]->longitude);
        $this->assertEquals(23.3, $points[2]->latitude);
        $this->assertEquals(55.7, $points[2]->longitude);
        $this->assertEquals(23.1, $points[3]->latitude);
        $this->assertEquals(55.5, $points[3]->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_stores_polygon_from_geo_json()
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'polygon' => Polygon::fromJson('{"type":"Polygon","coordinates":[[[55.5,23.1],[55.6,23.2],[55.7,23.3],[55.5,23.1]]]}'),
        ])->fresh();

        $this->assertTrue($testPlace->polygon instanceof Polygon);

        $lineStrings = $testPlace->polygon->getGeometries();
        $points = $lineStrings[0]->getGeometries();

        $this->assertEquals(23.1, $points[0]->latitude);
        $this->assertEquals(55.5, $points[0]->longitude);
        $this->assertEquals(23.2, $points[1]->latitude);
        $this->assertEquals(55.6, $points[1]->longitude);
        $this->assertEquals(23.3, $points[2]->latitude);
        $this->assertEquals(55.7, $points[2]->longitude);
        $this->assertEquals(23.1, $points[3]->latitude);
        $this->assertEquals(55.5, $points[3]->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_generates_polygon_geo_json()
    {
        $multiLineString = new Polygon([
            new LineString([
                new Point(23.1, 55.5),
                new Point(23.2, 55.6),
                new Point(23.3, 55.7),
                new Point(23.1, 55.5),
            ]),
        ]);

        $this->assertEquals('{"type":"Polygon","coordinates":[[[55.5,23.1],[55.6,23.2],[55.7,23.3],[55.5,23.1]]]}', $multiLineString->toJson());
    }
}
