<?php

namespace MatanYadaev\EloquentSpatial\Tests\Objects;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestCase;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class MultiPolygonTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_stores_multi_polygon()
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'multi_polygon' => new MultiPolygon([
                new Polygon([
                    new LineString([
                        new Point(23.1, 55.5),
                        new Point(23.2, 55.6),
                        new Point(23.3, 55.7),
                        new Point(23.1, 55.5),
                    ]),
                ]),
            ]),
        ])->fresh();

        $this->assertTrue($testPlace->multi_polygon instanceof MultiPolygon);

        $polygons = $testPlace->multi_polygon->getGeometries();
        $lineStrings = $polygons[0]->getGeometries();
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
    public function it_stores_multi_polygon_from_geo_json()
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'multi_polygon' => MultiPolygon::fromJson('{"type":"MultiPolygon","coordinates":[[[[55.5,23.1],[55.6,23.2],[55.7,23.3],[55.5,23.1]]]]}'),
        ])->fresh();

        $this->assertTrue($testPlace->multi_polygon instanceof MultiPolygon);

        $polygons = $testPlace->multi_polygon->getGeometries();
        $lineStrings = $polygons[0]->getGeometries();
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
    public function it_generates_multi_polygon_geo_json()
    {
        $multiPolygon = new MultiPolygon([
            new Polygon([
                new LineString([
                    new Point(23.1, 55.5),
                    new Point(23.2, 55.6),
                    new Point(23.3, 55.7),
                    new Point(23.1, 55.5),
                ]),
            ]),
        ]);

        $this->assertEquals('{"type":"MultiPolygon","coordinates":[[[[55.5,23.1],[55.6,23.2],[55.7,23.3],[55.5,23.1]]]]}', $multiPolygon->toJson());
    }
}
