<?php

namespace MatanYadaev\EloquentSpatial\Tests\TestModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Tests\TestFactories\TestExtendedPlaceFactory;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedGeometryCollection;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedLineString;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedMultiLineString;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedMultiPoint;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedMultiPolygon;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedPoint;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedPolygon;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

/**
 * @property ExtendedGeometryCollection $geometry_collection
 * @property ExtendedPoint $point
 * @property ExtendedMultiPoint $multi_point
 * @property ExtendedLineString $line_string
 * @property ExtendedMultiLineString $multi_line_string
 * @property ExtendedPolygon $polygon
 * @property ExtendedMultiPolygon $multi_polygon
 *
 * @mixin Model
 */
class TestExtendedPlace extends Model
{
    use HasFactory;
    use HasSpatial;

    protected $casts = [
        'geometry_collection' => ExtendedGeometryCollection::class,
        'line_string' => ExtendedLineString::class,
        'multi_line_string' => ExtendedMultiLineString::class,
        'multi_point' => ExtendedMultiPoint::class,
        'multi_polygon' => ExtendedMultiPolygon::class,
        'point' => ExtendedPoint::class,
        'polygon' => ExtendedPolygon::class,
    ];

    protected static function newFactory(): TestExtendedPlaceFactory
    {
        return TestExtendedPlaceFactory::new();
    }
}
