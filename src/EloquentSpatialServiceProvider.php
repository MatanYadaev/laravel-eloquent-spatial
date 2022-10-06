<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Doctrine\GeometryCollectionType;
use MatanYadaev\EloquentSpatial\Doctrine\LineStringType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiLineStringType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiPointType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiPolygonType;
use MatanYadaev\EloquentSpatial\Doctrine\PointType;
use MatanYadaev\EloquentSpatial\Doctrine\PolygonType;

class EloquentSpatialServiceProvider extends DatabaseServiceProvider
{
  public function boot(): void
  {
    $geometries = [
      'point' => PointType::class,
      'linestring' => LineStringType::class,
      'multipoint' => MultiPointType::class,
      'polygon' => PolygonType::class,
      'multilinestring' => MultiLineStringType::class,
      'multipolygon' => MultiPolygonType::class,
      'geometrycollection' => GeometryCollectionType::class,
      'geomcollection' => GeometryCollectionType::class,
    ];

    $connection = DB::connection();

    foreach ($geometries as $type => $class) {
      DB::registerDoctrineType($class, $type, $type);
      $connection->registerDoctrineType($class, $type, $type);
    }
  }
}
