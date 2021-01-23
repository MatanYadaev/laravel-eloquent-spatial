<?php

namespace MatanYadaev\EloquentSpatial\Tests\Objects;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\GeometryCollection;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestCase;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class GeometryCollectionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_stores_geometry_collection()
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'geometry_collection' => new GeometryCollection([
                new Polygon([
                    new LineString([
                        new Point(23.1, 55.5),
                        new Point(23.2, 55.6),
                        new Point(23.3, 55.7),
                        new Point(23.1, 55.5),
                    ]),
                ]),
                new Point(23.1, 55.5),
            ]),
        ])->fresh();

        $this->assertTrue($testPlace->geometry_collection instanceof GeometryCollection);

        $geometries = $testPlace->geometry_collection->getGeometries();
        /** @var Polygon $polygon */
        $polygon = $geometries[0];
        $lineStrings = $polygon->getGeometries();
        $points = $lineStrings[0]->getGeometries();

        $this->assertEquals(23.1, $points[0]->latitude);
        $this->assertEquals(55.5, $points[0]->longitude);
        $this->assertEquals(23.2, $points[1]->latitude);
        $this->assertEquals(55.6, $points[1]->longitude);
        $this->assertEquals(23.3, $points[2]->latitude);
        $this->assertEquals(55.7, $points[2]->longitude);
        $this->assertEquals(23.1, $points[3]->latitude);
        $this->assertEquals(55.5, $points[3]->longitude);

        /** @var Point $point */
        $point = $geometries[1];

        $this->assertEquals(23.1, $point->latitude);
        $this->assertEquals(55.5, $point->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_stores_geometry_collection_from_geo_json()
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'geometry_collection' => GeometryCollection::fromJson('{"type":"GeometryCollection","geometries":[{"type":"Polygon","coordinates":[[[55.5,23.1],[55.6,23.2],[55.7,23.3],[55.5,23.1]]]},{"type":"Point","coordinates":[55.5,23.1]}]}'),
        ])->fresh();

        $this->assertTrue($testPlace->geometry_collection instanceof GeometryCollection);

        $geometries = $testPlace->geometry_collection->getGeometries();
        /** @var Polygon $polygon */
        $polygon = $geometries[0];
        $lineStrings = $polygon->getGeometries();
        $points = $lineStrings[0]->getGeometries();

        $this->assertEquals(23.1, $points[0]->latitude);
        $this->assertEquals(55.5, $points[0]->longitude);
        $this->assertEquals(23.2, $points[1]->latitude);
        $this->assertEquals(55.6, $points[1]->longitude);
        $this->assertEquals(23.3, $points[2]->latitude);
        $this->assertEquals(55.7, $points[2]->longitude);
        $this->assertEquals(23.1, $points[3]->latitude);
        $this->assertEquals(55.5, $points[3]->longitude);

        /** @var Point $point */
        $point = $geometries[1];

        $this->assertEquals(23.1, $point->latitude);
        $this->assertEquals(55.5, $point->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_stores_geometry_collection_from_feature_collection_geo_json()
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'geometry_collection' => GeometryCollection::fromJson('{"type":"FeatureCollection","features":[{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[55.5,23.1],[55.6,23.2],[55.7,23.3],[55.5,23.1]]]}},{"type":"Feature","geometry":{"type":"Point","coordinates":[55.5,23.1]}}]}'),
        ])->fresh();

        $this->assertTrue($testPlace->geometry_collection instanceof GeometryCollection);

        $geometries = $testPlace->geometry_collection->getGeometries();
        /** @var Polygon $polygon */
        $polygon = $geometries[0];
        $lineStrings = $polygon->getGeometries();
        $points = $lineStrings[0]->getGeometries();

        $this->assertEquals(23.1, $points[0]->latitude);
        $this->assertEquals(55.5, $points[0]->longitude);
        $this->assertEquals(23.2, $points[1]->latitude);
        $this->assertEquals(55.6, $points[1]->longitude);
        $this->assertEquals(23.3, $points[2]->latitude);
        $this->assertEquals(55.7, $points[2]->longitude);
        $this->assertEquals(23.1, $points[3]->latitude);
        $this->assertEquals(55.5, $points[3]->longitude);

        /** @var Point $point */
        $point = $geometries[1];

        $this->assertEquals(23.1, $point->latitude);
        $this->assertEquals(55.5, $point->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_generates_multi_polygon_geo_json()
    {
        $geometryCollection = new GeometryCollection([
            new Polygon([
                new LineString([
                    new Point(23.1, 55.5),
                    new Point(23.2, 55.6),
                    new Point(23.3, 55.7),
                    new Point(23.1, 55.5),
                ]),
            ]),
            new Point(23.1, 55.5),
        ]);

        $this->assertEquals('{"type":"GeometryCollection","geometries":[{"type":"Polygon","coordinates":[[[55.5,23.1],[55.6,23.2],[55.7,23.3],[55.5,23.1]]]},{"type":"Point","coordinates":[55.5,23.1]}]}', $geometryCollection->toJson());
    }
}
