<?php

namespace MatanYadaev\EloquentSpatial\Tests\TestModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Builders\SpatialBuilder;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestFactories\TestPlaceFactory;

/**
 * @property Point $point
 * @property MultiPoint $multi_point
 * @property LineString $line_string
 * @property MultiLineString $multi_line_string
 * @property Polygon $polygon
 */
class TestPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'point',
        'multi_point',
        'line_string',
        'multi_line_string',
        'polygon',
    ];

    protected $casts = [
        'point' => Point::class,
        'multi_point' => MultiPoint::class,
        'line_string' => LineString::class,
        'multi_line_string' => MultiLineString::class,
        'polygon' => Polygon::class,
    ];

    public static function query(): SpatialBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }

    protected static function newFactory(): TestPlaceFactory
    {
        return new TestPlaceFactory;
    }
}
