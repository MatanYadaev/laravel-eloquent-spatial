<?php

namespace MatanYadaev\EloquentSpatial\Tests;

use Illuminate\Support\Facades\Config;
use MatanYadaev\EloquentSpatial\Objects\GeometryCollection;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
  protected function setUp(): void
  {
    parent::setUp();

    $this->resetGeometryClasses();

    $this->loadMigrationsFrom(__DIR__.'/database/migrations');
  }

  public function getEnvironmentSetUp($app): void
  {
    Config::set('database.default', 'mysql');
    Config::set('database.connections.mysql', [
      'driver' => 'mysql',
      'host' => '127.0.0.1',
      'port' => env('DB_PORT', 3306),
      'database' => 'laravel_eloquent_spatial_test',
      'username' => 'root',
    ]);
  }

  protected function resetGeometryClasses(): void
  {
    LaravelEloquentSpatial::$pointClass = Point::class;
    LaravelEloquentSpatial::$lineStringClass = LineString::class;
    LaravelEloquentSpatial::$multiPointClass = MultiPoint::class;
    LaravelEloquentSpatial::$polygonClass = Polygon::class;
    LaravelEloquentSpatial::$multiLineStringClass = MultiLineString::class;
    LaravelEloquentSpatial::$multiPolygonClass = MultiPolygon::class;
    LaravelEloquentSpatial::$geometryCollectionClass = GeometryCollection::class;
  }
}
