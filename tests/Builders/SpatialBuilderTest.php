<?php

namespace MatanYadaev\EloquentSpatial\Tests\Builders;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestCase;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class SpatialBuilderTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     * @environment-setup useMysql
     */
    public function it_calculates_distance_between_column_and_column()
    {
        TestPlace::factory()->create();

        $testPlaceWithDistance = TestPlace::query()
            ->withDistance('location', 'location')
            ->first();
        // @TODO add different column

        $this->assertEquals(0, $testPlaceWithDistance->distance);
    }

    /**
     * @test
     * @environment-setup useMysql
     */
    public function it_calculates_distance_between_column_and_geometry()
    {
        TestPlace::factory()->create([
            'location' => new Point(23.1, 55.5),
        ]);

        $testPlaceWithDistance = TestPlace::query()
            ->withDistance('location', new Point(23.1, 55.6))
            ->first();

        $this->assertEquals(0.1, $testPlaceWithDistance->distance);
    }

    /**
     * @test
     * @environment-setup useMysql
     */
    public function it_calculates_distance_with_defined_name()
    {
        TestPlace::factory()->create([
            'location' => new Point(23.1, 55.5),
        ]);

        $testPlaceWithDistance = TestPlace::query()
            ->withDistance('location', new Point(23.1, 55.6), 'distance_in_meters')
            ->first();

        $this->assertEquals(0.1, $testPlaceWithDistance->distance_in_meters);
    }

    /**
     * @test
     * @environment-setup useMysql
     */
    public function it_calculates_distance_sphere_column_and_column()
    {
        TestPlace::factory()->create();

        $testPlaceWithDistance = TestPlace::query()
            ->withDistanceSphere('location', 'location')
            ->first();

        $this->assertEquals(0, $testPlaceWithDistance->distance);
    }

    /**
     * @test
     * @environment-setup useMysql
     */
    public function it_calculates_distance_sphere_column_and_geometry()
    {
        TestPlace::factory()->create([
            'location' => new Point(23.1, 55.5),
        ]);

        $testPlaceWithDistance = TestPlace::query()
            ->withDistanceSphere('location', new Point(23.1, 55.51))
            ->first();

        $this->assertEquals(1022.7925914593363, $testPlaceWithDistance->distance);
    }

    /**
     * @test
     * @environment-setup useMysql
     */
    public function it_calculates_distance_sphere_with_defined_name()
    {
        TestPlace::factory()->create([
            'location' => new Point(23.1, 55.5),
        ]);

        $testPlaceWithDistance = TestPlace::query()
            ->withDistanceSphere('location', new Point(23.1, 55.51), 'distance_in_meters')
            ->first();

        $this->assertEquals(1022.7925914593363, $testPlaceWithDistance->distance_in_meters);
    }
}
