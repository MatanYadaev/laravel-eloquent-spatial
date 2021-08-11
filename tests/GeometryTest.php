<?php

namespace MatanYadaev\EloquentSpatial\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestFactories\TestPlaceFactory;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class GeometryTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_throws_exception_when_generating_geometry_from_invalid_value(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Point::fromJson('invalid-value');
    }

    /** @test */
    public function it_throws_exception_when_generating_geometry_from_other_geometry_wkb(): void
    {
        $this->expectException(InvalidArgumentException::class);

        TestPlace::insert(array_merge(TestPlace::factory()->definition(), [
            'point_with_line_string_cast' => DB::raw('POINT(0, 180)'),
        ]));

        TestPlace::firstOrFail()->getAttribute('point_with_line_string_cast');
    }

    /** @test */
    public function it_throws_exception_when_generating_geometry_from_invalid_geo_json(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Point::fromJson('{}');
    }

    /** @test */
    public function it_throws_exception_when_generating_geometry_from_other_geometry_geo_json(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Point::fromJson('{"type":"LineString","coordinates":[[0,180],[1,179]]}');
    }
}
