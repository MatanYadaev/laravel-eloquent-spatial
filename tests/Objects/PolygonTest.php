<?php

namespace MatanYadaev\EloquentSpatial\Tests\Objects;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestCase;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class PolygonTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_stores_polygon(): void
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'polygon' => new Polygon([
                new LineString([
                    new Point(180, 0),
                    new Point(179, 1),
                    new Point(178, 2),
                    new Point(177, 3),
                    new Point(180, 0),
                ]),
            ]),
        ]);

        $this->assertTrue($testPlace->polygon instanceof Polygon);

        $lineStrings = $testPlace->polygon->getGeometries();
        $points = $lineStrings[0]->getGeometries();

        $this->assertEquals(180, $points[0]->latitude);
        $this->assertEquals(0, $points[0]->longitude);
        $this->assertEquals(179, $points[1]->latitude);
        $this->assertEquals(1, $points[1]->longitude);
        $this->assertEquals(178, $points[2]->latitude);
        $this->assertEquals(2, $points[2]->longitude);
        $this->assertEquals(177, $points[3]->latitude);
        $this->assertEquals(3, $points[3]->longitude);
        $this->assertEquals(180, $points[4]->latitude);
        $this->assertEquals(0, $points[4]->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_stores_polygon_from_geo_json(): void
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'polygon' => Polygon::fromJson('{"type":"Polygon","coordinates":[[[0,180],[1,179],[2,178],[3,177],[0,180]]]}'),
        ]);

        $this->assertTrue($testPlace->polygon instanceof Polygon);

        $lineStrings = $testPlace->polygon->getGeometries();
        $points = $lineStrings[0]->getGeometries();

        $this->assertEquals(180, $points[0]->latitude);
        $this->assertEquals(0, $points[0]->longitude);
        $this->assertEquals(179, $points[1]->latitude);
        $this->assertEquals(1, $points[1]->longitude);
        $this->assertEquals(178, $points[2]->latitude);
        $this->assertEquals(2, $points[2]->longitude);
        $this->assertEquals(177, $points[3]->latitude);
        $this->assertEquals(3, $points[3]->longitude);
        $this->assertEquals(180, $points[4]->latitude);
        $this->assertEquals(0, $points[4]->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_generates_polygon_geo_json(): void
    {
        $polygon = new Polygon([
            new LineString([
                new Point(180, 0),
                new Point(179, 1),
                new Point(178, 2),
                new Point(177, 3),
                new Point(180, 0),
            ]),
        ]);

        $this->assertEquals('{"type":"Polygon","coordinates":[[[0,180],[1,179],[2,178],[3,177],[0,180]]]}', $polygon->toJson());
    }

    /** @test */
    public function it_generates_polygon_feature_collection_json(): void
    {
        $polygon = new Polygon([
            new LineString([
                new Point(180, 0),
                new Point(179, 1),
                new Point(178, 2),
                new Point(177, 3),
                new Point(180, 0),
            ]),
        ]);

        $this->assertEquals('{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"Polygon","coordinates":[[[0,180],[1,179],[2,178],[3,177],[0,180]]]}}]}', $polygon->toFeatureCollectionJson());
    }
}
