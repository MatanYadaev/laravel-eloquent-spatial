<?php

namespace MatanYadaev\EloquentSpatial\Tests\TestModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\GeometryCollection;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\SpatialBuilder;
use MatanYadaev\EloquentSpatial\Tests\TestFactories\TestPlaceFactory;
use MatanYadaev\EloquentSpatial\Traits\HasSpatialColumns;

/**
 * @property Point $point
 * @property MultiPoint $multi_point
 * @property LineString $line_string
 * @property MultiLineString $multi_line_string
 * @property Polygon $polygon
 * @property MultiPolygon $multi_polygon
 * @property GeometryCollection $geometry_collection
 * @property float|null $distance
 * @property float|null $distance_in_meters
 * @mixin Model
 *
 * @method static SpatialBuilder<TestPlace> query()
 */
class TestPlace extends Model
{
    use HasSpatialColumns, HasFactory;

    protected $fillable = [
        'address',
        'point',
        'multi_point',
        'line_string',
        'multi_line_string',
        'polygon',
        'multi_polygon',
        'geometry_collection',
        'point_with_line_string_cast',
    ];

    /**
     * @var array<string, mixed>
     */
    protected $casts = [
        'point' => Point::class,
        'multi_point' => MultiPoint::class,
        'line_string' => LineString::class,
        'multi_line_string' => MultiLineString::class,
        'polygon' => Polygon::class,
        'multi_polygon' => MultiPolygon::class,
        'geometry_collection' => GeometryCollection::class,
        'point_with_line_string_cast' => LineString::class,
    ];

    protected static function newFactory(): TestPlaceFactory
    {
        return new TestPlaceFactory;
    }
}
