<?php

namespace MatanYadaev\EloquentSpatial\Tests\Objects;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestCase;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class MultiLineStringTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_stores_multi_line_string(): void
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'multi_line_string' => new MultiLineString([
                new LineString([
                    new Point(0, 0),
                    new Point(1, 1),
                ]),
            ]),
        ])->fresh();

        $this->assertTrue($testPlace->multi_line_string instanceof MultiLineString);

        $lineStrings = $testPlace->multi_line_string->getGeometries();
        $points = $lineStrings[0]->getGeometries();

        $this->assertEquals(0, $points[0]->latitude);
        $this->assertEquals(0, $points[0]->longitude);
        $this->assertEquals(1, $points[1]->latitude);
        $this->assertEquals(1, $points[1]->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_stores_multi_line_string_from_geo_json(): void
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'multi_line_string' => MultiLineString::fromJson('{"type":"MultiLineString","coordinates":[[[0,0],[1,1]]]}'),
        ])->fresh();

        $this->assertTrue($testPlace->multi_line_string instanceof MultiLineString);

        $lineStrings = $testPlace->multi_line_string->getGeometries();
        $points = $lineStrings[0]->getGeometries();

        $this->assertEquals(0, $points[0]->latitude);
        $this->assertEquals(0, $points[0]->longitude);
        $this->assertEquals(1, $points[1]->latitude);
        $this->assertEquals(1, $points[1]->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_generates_multi_line_string_geo_json(): void
    {
        $multiLineString = new MultiLineString([
            new LineString([
                new Point(0, 0),
                new Point(1, 1),
            ]),
        ]);

        $this->assertEquals('{"type":"MultiLineString","coordinates":[[[0,0],[1,1]]]}', $multiLineString->toJson());
    }

    /** @test */
    public function it_generates_multi_line_string_feature_collection_json(): void
    {
        $multiLineString = new MultiLineString([
            new LineString([
                new Point(0, 0),
                new Point(1, 1),
            ]),
        ]);

        $this->assertEquals('{"type":"FeatureCollection","features":[{"type":"MultiLineString","coordinates":[[[0,0],[1,1]]]}]}', $multiLineString->toFeatureCollectionJson());
    }
}
