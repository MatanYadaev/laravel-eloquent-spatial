<?php

namespace MatanYadaev\EloquentSpatial\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use InvalidArgumentException;
use MatanYadaev\EloquentSpatial\Objects\Point;

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
