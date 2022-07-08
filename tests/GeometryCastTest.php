<?php

namespace MatanYadaev\EloquentSpatial\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class GeometryCastTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_serializes_and_deserializes_geometry_object(): void
    {
        $point = new Point(180, 0);

        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'point' => $point,
        ]);

        $this->assertEquals($point, $testPlace->point);
    }

    /** @test */
    public function it_gets_original_geometry_field(): void
    {
        $point = new Point(180, 0);
        $point2 = new Point(180, 0);

        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'point' => $point,
        ]);
        $testPlace->point = $point2;
        $testPlace->save();

        $this->assertEquals($point, $testPlace->getOriginal('point'));
    }

    /** @test */
    public function it_gets_dirty_when_geometry_is_changed(): void
    {
        $point = new Point(180, 0);
        $point2 = new Point(0, 0);

        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'point' => $point,
        ]);
        $testPlace->point = $point2;

        $this->assertTrue($testPlace->isDirty('point'));
    }

    /** @test */
    public function it_does_not_get_dirty_when_geometry_is_not_changed(): void
    {
        $point = new Point(180, 0);
        $point2 = new Point(180, 0);

        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'point' => $point,
        ]);

        $this->assertFalse($testPlace->isDirty('point'));
    }

    /** @test */
    public function it_serializes_model_to_array_with_geometry(): void
    {
        $point = new Point(180, 0);

        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'point' => $point,
        ]);

        $this->assertEquals($point->toArray(), $testPlace->toArray()['point']);
    }

    /** @test */
    public function it_serializes_model_to_json_with_geometry(): void
    {
        $point = new Point(180, 0);

        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'point' => $point,
        ]);

        $json = $testPlace->toJson();
        // @phpstan-ignore-next-line
        $jsonOfPoint = json_encode(json_decode($json, true)['point']);

        $this->assertEquals($point->toJson(), $jsonOfPoint);
    }

    /** @test */
    public function it_throws_exception_when_serializing_invalid_geometry_object(): void
    {
        $this->expectException(InvalidArgumentException::class);

        TestPlace::factory()->make([
            'point' => new LineString([
                new Point(180, 0),
                new Point(179, 1),
            ]),
        ]);
    }

    /** @test */
    public function it_throws_exception_when_serializing_invalid_type(): void
    {
        $this->expectException(InvalidArgumentException::class);

        TestPlace::factory()->make([
            'point' => 'not-a-point-object',
        ]);
    }

    /** @test */
    public function it_throws_exception_when_deserializing_invalid_geometry_object(): void
    {
        $this->expectException(InvalidArgumentException::class);

        TestPlace::insert(array_merge(TestPlace::factory()->definition(), [
            'point_with_line_string_cast' => DB::raw('POINT(0, 180)'),
        ]));

        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::firstOrFail();

        $testPlace->getAttribute('point_with_line_string_cast');
    }

    /** @test */
    public function it_serializes_and_deserializes_null(): void
    {
        /** @var TestPlace $testPlace */
        $testPlace = TestPlace::factory()->create([
            'point' => null,
        ]);

        $this->assertEquals(null, $testPlace->point);
    }
}
