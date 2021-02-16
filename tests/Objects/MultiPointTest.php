<?php

namespace MatanYadaev\EloquentSpatial\Tests\Objects;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use InvalidArgumentException;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestCase;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class MultiPointTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_stores_multi_point(): void
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'multi_point' => new MultiPoint([
                new Point(180, 0),
            ]),
        ]);

        $this->assertTrue($testPlace->multi_point instanceof MultiPoint);

        $this->assertEquals(180, $testPlace->multi_point[0]->latitude);
        $this->assertEquals(0, $testPlace->multi_point[0]->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_stores_multi_point_from_json(): void
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'multi_point' => MultiPoint::fromJson('{"type":"MultiPoint","coordinates":[[0,180]]}'),
        ]);

        $this->assertTrue($testPlace->multi_point instanceof MultiPoint);

        $this->assertEquals(180, $testPlace->multi_point[0]->latitude);
        $this->assertEquals(0, $testPlace->multi_point[0]->longitude);

        $this->assertDatabaseCount($testPlace->getTable(), 1);
    }

    /** @test */
    public function it_generates_multi_point_geo_json(): void
    {
        $multiPoint = new MultiPoint([
            new Point(180, 0),
        ]);

        $this->assertEquals('{"type":"MultiPoint","coordinates":[[0,180]]}', $multiPoint->toJson());
    }

    /** @test */
    public function it_generates_multi_point_feature_collection_json(): void
    {
        $multiPoint = new MultiPoint([
            new Point(180, 0),
        ]);

        $this->assertEquals('{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"MultiPoint","coordinates":[[0,180]]}}]}', $multiPoint->toFeatureCollectionJson());
    }

    /** @test */
    public function it_throws_exception_when_multi_point_has_0_points(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new MultiPoint([]);
    }

    /** @test */
    public function it_throws_exception_when_multi_point_has_composed_by_polygon(): void
    {
        $this->expectException(InvalidArgumentException::class);

        // @phpstan-ignore-next-line
        new MultiLineString([
            Polygon::fromJson('{"type":"Polygon","coordinates":[[[0,180],[1,179],[2,178],[3,177],[0,180]]]}'),
        ]);
    }
}
