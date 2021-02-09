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
    public function it_stores_line_string(): void
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'line_string' => new LineString([
                new Point(0, 0),
                new Point(1, 1),
            ]),
        ])->fresh();

        $this->assertTrue($testPlace->line_string instanceof LineString);

        $points = $testPlace->line_string->getGeometries();

        $this->assertEquals(0, $points[0]->latitude);
        $this->assertEquals(0, $points[0]->longitude);
        $this->assertEquals(1, $points[1]->latitude);
        $this->assertEquals(1, $points[1]->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_stores_line_string_from_json(): void
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'line_string' => LineString::fromJson('{"type":"LineString","coordinates":[[0,0],[1,1]]}'),
        ])->fresh();

        $this->assertTrue($testPlace->line_string instanceof LineString);

        $points = $testPlace->line_string->getGeometries();

        $this->assertEquals(0, $points[0]->latitude);
        $this->assertEquals(0, $points[0]->longitude);
        $this->assertEquals(1, $points[1]->latitude);
        $this->assertEquals(1, $points[1]->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_generates_line_string_geo_json(): void
    {
        $lineString = new LineString([
            new Point(0, 0),
            new Point(1, 1),
        ]);

        $this->assertEquals('{"type":"LineString","coordinates":[[0,0],[1,1]]}', $lineString->toJson());
    }

    /** @test */
    public function it_generates_line_string_feature_collection_json(): void
    {
        $lineString = new LineString([
            new Point(0, 0),
            new Point(1, 1),
        ]);

        $this->assertEquals('{"type":"FeatureCollection","features":[{"type":"LineString","coordinates":[[0,0],[1,1]]}]}', $lineString->toFeatureCollectionJson());
    }
}
