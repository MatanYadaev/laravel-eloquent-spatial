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
use MatanYadaev\EloquentSpatial\Tests\TestFactories\TestExtendedPlaceFactory;
use MatanYadaev\EloquentSpatial\Tests\TestFactories\TestPlaceFactory;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedGeometryCollection;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedLineString;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedMultiLineString;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedMultiPoint;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedMultiPolygon;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedPoint;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedPolygon;

/**
 * @property ExtendedPoint $point
 * @property ExtendedMultiPoint $multi_point
 * @property ExtendedLineString $line_string
 * @property ExtendedMultiLineString $multi_line_string
 * @property ExtendedPolygon $polygon
 * @property ExtendedMultiPolygon $multi_polygon
 * @property ExtendedGeometryCollection $geometry_collection
 * @mixin Model
 *
 * @method static SpatialBuilder<TestExtendedPlace> query()
 */
class TestExtendedPlace extends Model
{
  use HasFactory;

  protected $casts = [
    'point' => ExtendedPoint::class,
    'multi_point' => ExtendedMultiPoint::class,
    'line_string' => ExtendedLineString::class,
    'multi_line_string' => ExtendedMultiLineString::class,
    'polygon' => ExtendedPolygon::class,
    'multi_polygon' => ExtendedMultiPolygon::class,
    'geometry_collection' => ExtendedGeometryCollection::class,
  ];

  /**
   * @param $query
   * @return SpatialBuilder<TestExtendedPlace>
   */
  public function newEloquentBuilder($query): SpatialBuilder
  {
    // @phpstan-ignore-next-line
    return new SpatialBuilder($query);
  }

  protected static function newFactory(): TestExtendedPlaceFactory
  {
    return TestExtendedPlaceFactory::new();
  }
}
