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
    public function it_stores_geometry_collection(): void
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'geometry_collection' => new GeometryCollection([
                new Polygon([
                    new LineString([
                        new Point(0, 0),
                        new Point(1, 1),
                        new Point(2, 2),
                        new Point(3, 3),
                        new Point(0, 0),
                    ]),
                ]),
                new Point(0, 0),
            ]),
        ])->fresh();

        $this->assertTrue($testPlace->geometry_collection instanceof GeometryCollection);

        $geometries = $testPlace->geometry_collection->getGeometries();
        /** @var Polygon $polygon */
        $polygon = $geometries[0];
        $lineStrings = $polygon->getGeometries();
        $points = $lineStrings[0]->getGeometries();

        $this->assertEquals(0, $points[0]->latitude);
        $this->assertEquals(0, $points[0]->longitude);
        $this->assertEquals(1, $points[1]->latitude);
        $this->assertEquals(1, $points[1]->longitude);
        $this->assertEquals(2, $points[2]->latitude);
        $this->assertEquals(2, $points[2]->longitude);
        $this->assertEquals(3, $points[3]->latitude);
        $this->assertEquals(3, $points[3]->longitude);
        $this->assertEquals(0, $points[4]->latitude);
        $this->assertEquals(0, $points[4]->longitude);

        /** @var Point $point */
        $point = $geometries[1];

        $this->assertEquals(0, $point->latitude);
        $this->assertEquals(0, $point->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_stores_geometry_collection_from_geo_json(): void
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'geometry_collection' => GeometryCollection::fromJson('{"type":"GeometryCollection","geometries":[{"type":"Polygon","coordinates":[[[0,0],[1,1],[2,2],[3,3],[0,0]]]},{"type":"Point","coordinates":[0,0]}]}'),
        ])->fresh();

        $this->assertTrue($testPlace->geometry_collection instanceof GeometryCollection);

        $geometries = $testPlace->geometry_collection->getGeometries();
        /** @var Polygon $polygon */
        $polygon = $geometries[0];
        $lineStrings = $polygon->getGeometries();
        $points = $lineStrings[0]->getGeometries();

        $this->assertEquals(0, $points[0]->latitude);
        $this->assertEquals(0, $points[0]->longitude);
        $this->assertEquals(1, $points[1]->latitude);
        $this->assertEquals(1, $points[1]->longitude);
        $this->assertEquals(2, $points[2]->latitude);
        $this->assertEquals(2, $points[2]->longitude);
        $this->assertEquals(3, $points[3]->latitude);
        $this->assertEquals(3, $points[3]->longitude);
        $this->assertEquals(0, $points[4]->latitude);
        $this->assertEquals(0, $points[4]->longitude);

        /** @var Point $point */
        $point = $geometries[1];

        $this->assertEquals(0, $point->latitude);
        $this->assertEquals(0, $point->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_stores_geometry_collection_from_feature_collection_geo_json(): void
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'geometry_collection' => GeometryCollection::fromJson('{"type":"FeatureCollection","features":[{"type":"Polygon","coordinates":[[[0,0],[1,1],[2,2],[3,3],[0,0]]]},{"type":"Point","coordinates":[0,0]}]}'),
        ])->fresh();

        $this->assertTrue($testPlace->geometry_collection instanceof GeometryCollection);

        $geometries = $testPlace->geometry_collection->getGeometries();
        /** @var Polygon $polygon */
        $polygon = $geometries[0];
        $lineStrings = $polygon->getGeometries();
        $points = $lineStrings[0]->getGeometries();

        $this->assertEquals(0, $points[0]->latitude);
        $this->assertEquals(0, $points[0]->longitude);
        $this->assertEquals(1, $points[1]->latitude);
        $this->assertEquals(1, $points[1]->longitude);
        $this->assertEquals(2, $points[2]->latitude);
        $this->assertEquals(2, $points[2]->longitude);
        $this->assertEquals(3, $points[3]->latitude);
        $this->assertEquals(3, $points[3]->longitude);
        $this->assertEquals(0, $points[4]->latitude);
        $this->assertEquals(0, $points[4]->longitude);

        /** @var Point $point */
        $point = $geometries[1];

        $this->assertEquals(0, $point->latitude);
        $this->assertEquals(0, $point->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_generates_geometry_collection_geo_json(): void
    {
        $geometryCollection = new GeometryCollection([
            new Polygon([
                new LineString([
                    new Point(0, 0),
                    new Point(1, 1),
                    new Point(2, 2),
                    new Point(3, 3),
                    new Point(0, 0),
                ]),
            ]),
            new Point(0, 0),
        ]);

        $this->assertEquals(
            '{"type":"GeometryCollection","geometries":[{"type":"Polygon","coordinates":[[[0,0],[1,1],[2,2],[3,3],[0,0]]]},{"type":"Point","coordinates":[0,0]}]}',
            $geometryCollection->toJson()
        );
    }

    /** @test */
    public function it_generates_geometry_collection_feature_collection_json(): void
    {
        $geometryCollection = new GeometryCollection([
            new Polygon([
                new LineString([
                    new Point(0, 0),
                    new Point(1, 1),
                    new Point(2, 2),
                    new Point(3, 3),
                    new Point(0, 0),
                ]),
            ]),
            new Point(0, 0),
        ]);

        $this->assertEquals(
            '{"type":"FeatureCollection","features":[{"type":"Polygon","coordinates":[[[0,0],[1,1],[2,2],[3,3],[0,0]]]},{"type":"Point","coordinates":[0,0]}]}',
            $geometryCollection->toFeatureCollectionJson()
        );
    }
}
