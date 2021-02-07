<?php

namespace MatanYadaev\EloquentSpatial\Tests\Builders;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestCase;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class SpatialBuilderTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_calculates_distance_between_column_and_column(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(23.1, 55.5),
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
            'point' => new Point(23.1, 55.5),
        ]);

        /** @var TestPlace $testPlaceWithDistance */
        $testPlaceWithDistance = TestPlace::query()
            ->withDistance('point', new Point(23.1, 55.6))
            ->firstOrFail();

        $this->assertEquals(0.1, $testPlaceWithDistance->distance);
    }

    /** @test */
    public function it_calculates_distance_with_defined_name(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(23.1, 55.5),
        ]);

        /** @var TestPlace $testPlaceWithDistance */
        $testPlaceWithDistance = TestPlace::query()
            ->withDistance('point', new Point(23.1, 55.6), 'distance_in_meters')
            ->firstOrFail();

        $this->assertEquals(0.1, $testPlaceWithDistance->distance_in_meters);
    }

    /** @test */
    public function it_filters_by_distance(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(23.1, 55.5),
        ]);

        /** @var TestPlace $testPlaceWithinDistance */
        $testPlaceWithinDistance = TestPlace::query()
            ->whereDistance('point', new Point(23.1, 55.6), '<', 1)
            ->firstOrFail();

        $this->assertNotNull($testPlaceWithinDistance);
    }

    /** @test */
    public function it_orders_by_distance(): void
    {
        $point = new Point(23.1, 55.51);
        $testPlace1 = TestPlace::factory()->create([
            'point' => new Point(23.1, 55.5),
        ]);
        $testPlace2 = TestPlace::factory()->create([
            'point' => new Point(0, 0),
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
        TestPlace::factory()->create();

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
            'point' => new Point(23.1, 55.5),
        ]);

        /** @var TestPlace $testPlaceWithDistance */
        $testPlaceWithDistance = TestPlace::query()
            ->withDistanceSphere('point', new Point(23.1, 55.51))
            ->firstOrFail();

        $this->assertEquals(1022.7925914593363, $testPlaceWithDistance->distance);
    }

    /** @test */
    public function it_calculates_distance_sphere_with_defined_name(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(23.1, 55.5),
        ]);

        /** @var TestPlace $testPlaceWithDistance */
        $testPlaceWithDistance = TestPlace::query()
            ->withDistanceSphere('point', new Point(23.1, 55.51), 'distance_in_meters')
            ->firstOrFail();

        $this->assertEquals(1022.7925914593363, $testPlaceWithDistance->distance_in_meters);
    }

    /** @test */
    public function it_filters_distance_sphere(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(23.1, 55.5),
        ]);

        /** @var TestPlace $testPlaceWithinDistanceSphere */
        $testPlaceWithinDistanceSphere = TestPlace::query()
            ->whereDistanceSphere('point', new Point(23.1, 55.51), '<', 2000)
            ->firstOrFail();

        $this->assertNotNull($testPlaceWithinDistanceSphere);
    }

    /** @test */
    public function it_orders_by_distance_sphere(): void
    {
        $point = new Point(23.1, 55.51);
        $testPlace1 = TestPlace::factory()->create([
            'point' => new Point(23.1, 55.5),
        ]);
        $testPlace2 = TestPlace::factory()->create([
            'point' => new Point(0, 0),
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
            'point' => new Point(23.1, 55.5),
        ]);

        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::query()
            ->whereWithin('point', MultiPolygon::fromJson('{"type":"MultiPolygon","coordinates":[[[[55.5,23.0],[55.4,23.2],[55.8,23.3],[55.5,23.0]]]]}'))
            ->firstOrFail();

        $this->assertNotNull($testPlace);
    }

    /** @test */
    public function it_filters_by_contains(): void
    {
        TestPlace::factory()->create([
            'multi_polygon' => MultiPolygon::fromJson('{"type":"MultiPolygon","coordinates":[[[[55.5,23.0],[55.4,23.2],[55.8,23.3],[55.5,23.0]]]]}'),
        ]);

        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::query()
            ->whereContains('multi_polygon', new Point(23.1, 55.5))
            ->firstOrFail();

        $this->assertNotNull($testPlace);
    }

    /** @test */
    public function it_filters_by_touches(): void
    {
        TestPlace::factory()->create([
            'point' => new Point(23.1, 55.5),
        ]);

        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::query()
            ->whereTouches('point', MultiPolygon::fromJson('{"type":"MultiPolygon","coordinates":[[[[55.5,23.1],[55.6,23.2],[55.7,23.3],[55.5,23.1]]]]}'))
            ->firstOrFail();

        $this->assertNotNull($testPlace);
    }
}
