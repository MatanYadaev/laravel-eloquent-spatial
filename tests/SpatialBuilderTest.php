<?php

namespace MatanYadaev\EloquentSpatial\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class SpatialBuilderTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_calculates_distance_between_column_and_column(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(0, 0),
        ]);

        /** @var TestPlace $testPlaceWithDistance */
        $testPlaceWithDistance = TestPlace::query()
            ->withDistance('point', 'point')
            ->firstOrFail();

        $this->assertEquals(0, $testPlaceWithDistance->distance);
    }

    /** @test */
    public function it_calculates_distance_between_column_and_geometry(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(0, 0),
        ]);

        /** @var TestPlace $testPlaceWithDistance */
        $testPlaceWithDistance = TestPlace::query()
            ->withDistance('point', new Point(1, 1))
            ->firstOrFail();

        $this->assertEquals(1.4142135623731, $testPlaceWithDistance->distance);
    }

    /** @test */
    public function it_calculates_distance_with_defined_name(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(0, 0),
        ]);

        /** @var TestPlace $testPlaceWithDistance */
        $testPlaceWithDistance = TestPlace::query()
            ->withDistance('point', new Point(1, 1), 'distance_in_meters')
            ->firstOrFail();

        $this->assertEquals(1.4142135623731, $testPlaceWithDistance->distance_in_meters);
    }

    /** @test */
    public function it_filters_by_distance(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(0, 0),
        ]);

        $testPlaceWithinDistance = TestPlace::query()
            ->whereDistance('point', new Point(1, 1), '<', 10)
            ->first();

        $this->assertNotNull($testPlaceWithinDistance);
    }

    /** @test */
    public function it_orders_by_distance(): void
    {
        $point = new Point(0, 0);
        $testPlace1 = TestPlace::factory()->create([
            'point' => new Point(1, 1),
        ]);
        $testPlace2 = TestPlace::factory()->create([
            'point' => new Point(2, 2),
        ]);

        /** @var TestPlace $closestTestPlace */
        $closestTestPlace = TestPlace::query()
            ->orderByDistance('point', $point)
            ->firstOrFail();

        /** @var TestPlace $farthestTestPlace */
        $farthestTestPlace = TestPlace::query()
            ->orderByDistance('point', $point, 'desc')
            ->firstOrFail();

        $this->assertEquals($testPlace1->id, $closestTestPlace->id);
        $this->assertEquals($testPlace2->id, $farthestTestPlace->id);
    }

    /** @test */
    public function it_calculates_distance_sphere_column_and_column(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(0, 0),
        ]);

        /** @var TestPlace $testPlaceWithDistance */
        $testPlaceWithDistance = TestPlace::query()
            ->withDistanceSphere('point', 'point')
            ->firstOrFail();

        $this->assertEquals(0, $testPlaceWithDistance->distance);
    }

    /** @test */
    public function it_calculates_distance_sphere_column_and_geometry(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(0, 0),
        ]);

        /** @var TestPlace $testPlaceWithDistance */
        $testPlaceWithDistance = TestPlace::query()
            ->withDistanceSphere('point', new Point(1, 1))
            ->firstOrFail();

        $this->assertEquals(157249.0357231545, $testPlaceWithDistance->distance);
    }

    /** @test */
    public function it_calculates_distance_sphere_with_defined_name(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(0, 0),
        ]);

        /** @var TestPlace $testPlaceWithDistance */
        $testPlaceWithDistance = TestPlace::query()
            ->withDistanceSphere('point', new Point(1, 1), 'distance_in_meters')
            ->firstOrFail();

        $this->assertEquals(157249.0357231545, $testPlaceWithDistance->distance_in_meters);
    }

    /** @test */
    public function it_filters_distance_sphere(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(0, 0),
        ]);

        $testPlaceWithinDistanceSphere = TestPlace::query()
            ->whereDistanceSphere('point', new Point(1, 1), '<', 200000)
            ->first();

        $this->assertNotNull($testPlaceWithinDistanceSphere);
    }

    /** @test */
    public function it_orders_by_distance_sphere(): void
    {
        $point = new Point(0, 0);
        $testPlace1 = TestPlace::factory()->create([
            'point' => new Point(1, 1),
        ]);
        $testPlace2 = TestPlace::factory()->create([
            'point' => new Point(2, 2),
        ]);

        /** @var TestPlace $closestTestPlace */
        $closestTestPlace = TestPlace::query()
            ->orderByDistanceSphere('point', $point)
            ->firstOrFail();

        /** @var TestPlace $farthestTestPlace */
        $farthestTestPlace = TestPlace::query()
            ->orderByDistanceSphere('point', $point, 'desc')
            ->firstOrFail();

        $this->assertEquals($testPlace1->id, $closestTestPlace->id);
        $this->assertEquals($testPlace2->id, $farthestTestPlace->id);
    }

    /** @test */
    public function it_filters_by_within(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(0, 0),
        ]);

        $testPlace = TestPlace::query()
            ->whereWithin('point', Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}'))
            ->first();

        $this->assertNotNull($testPlace);
    }

    /** @test */
    public function it_filters_by_contains(): void
    {
        TestPlace::factory()->create([
            'polygon' => Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}'),
        ]);

        $testPlace = TestPlace::query()
            ->whereContains('polygon', new Point(0, 0))
            ->first();

        $this->assertNotNull($testPlace);
    }

    /** @test */
    public function it_filters_by_touches(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(0, 0),
        ]);

        $testPlace = TestPlace::query()
            ->whereTouches('point', Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[0,-1],[0,0],[-1,0],[-1,-1]]]}'))
            ->first();

        $this->assertNotNull($testPlace);
    }

    /** @test */
    public function it_filters_by_intersects(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(0, 0),
        ]);

        $testPlace = TestPlace::query()
            ->whereIntersects('point', Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}'))
            ->first();

        $this->assertNotNull($testPlace);
    }

    /** @test */
    public function it_filters_by_crosses(): void
    {
        TestPlace::factory()->create([
            'line_string' => LineString::fromJson('{"type":"LineString","coordinates":[[0,0],[2,0]]}'),
        ]);

        $testPlace = TestPlace::query()
            ->whereCrosses('line_string', Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}'))
            ->first();

        $this->assertNotNull($testPlace);
    }

    /** @test */
    public function it_filters_by_disjoint(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(0, 0),
        ]);

        $testPlace = TestPlace::query()
            ->whereDisjoint('point', Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[-0.5,-1],[-0.5,-0.5],[-1,-0.5],[-1,-1]]]}'))
            ->first();

        $this->assertNotNull($testPlace);
    }

    /** @test */
    public function it_filters_by_overlaps(): void
    {
        TestPlace::factory()->create([
            'polygon' => Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[-0.5,-1],[-0.5,-0.5],[-1,-0.5],[-1,-1]]]}'),
        ]);

        $testPlace = TestPlace::query()
            ->whereOverlaps('polygon', Polygon::fromJson('{"type":"Polygon","coordinates":[[[-0.75,-0.75],[1,-1],[1,1],[-1,1],[-0.75,-0.75]]]}'))
            ->first();

        $this->assertNotNull($testPlace);
    }

    /** @test */
    public function it_filters_by_equals(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(0, 0),
        ]);

        $testPlace = TestPlace::query()
            ->whereEquals('point', new Point(0, 0))
            ->first();

        $this->assertNotNull($testPlace);
    }
    
    /** @test */
    public function it_filters_by_srid(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(30.0526012,31.2772677,4326),
        ]);

        $testPlace = TestPlace::query()
            ->whereSrid('point', '=',4326)
            ->first();

        $this->assertNotNull($testPlace);
    }
}
