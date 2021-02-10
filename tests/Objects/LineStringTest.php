<?php

namespace MatanYadaev\EloquentSpatial\Tests\Objects;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use InvalidArgumentException;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
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
                new Point(180, 0),
                new Point(179, 1),
            ]),
        ]);

        $this->assertTrue($testPlace->line_string instanceof LineString);

        $points = $testPlace->line_string->getGeometries();

        $this->assertEquals(180, $points[0]->latitude);
        $this->assertEquals(0, $points[0]->longitude);
        $this->assertEquals(179, $points[1]->latitude);
        $this->assertEquals(1, $points[1]->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_stores_line_string_from_json(): void
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'line_string' => LineString::fromJson('{"type":"LineString","coordinates":[[0,180],[1,179]]}'),
        ]);

        $this->assertTrue($testPlace->line_string instanceof LineString);

        $points = $testPlace->line_string->getGeometries();

        $this->assertEquals(180, $points[0]->latitude);
        $this->assertEquals(0, $points[0]->longitude);
        $this->assertEquals(179, $points[1]->latitude);
        $this->assertEquals(1, $points[1]->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_generates_line_string_geo_json(): void
    {
        $lineString = new LineString([
            new Point(180, 0),
            new Point(179, 1),
        ]);

        $this->assertEquals('{"type":"LineString","coordinates":[[0,180],[1,179]]}', $lineString->toJson());
    }

    /** @test */
    public function it_generates_line_string_feature_collection_json(): void
    {
        $lineString = new LineString([
            new Point(180, 0),
            new Point(179, 1),
        ]);

        $this->assertEquals('{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"LineString","coordinates":[[0,180],[1,179]]}}]}', $lineString->toFeatureCollectionJson());
    }

    /** @test */
    public function it_throws_exception_when_line_string_has_less_than_2_points(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new LineString([
            new Point(180, 0),
        ]);
    }

    /** @test */
    public function it_throws_exception_when_line_string_has_composed_by_polygon(): void
    {
        $this->expectException(InvalidArgumentException::class);

        // @phpstan-ignore-next-line
        new LineString([
            Polygon::fromJson('{"type":"Polygon","coordinates":[[[0,180],[1,179],[2,178],[3,177],[0,180]]]}'),
        ]);
    }
}
