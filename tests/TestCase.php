<?php

namespace MatanYadaev\EloquentSpatial\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use MatanYadaev\EloquentSpatial\EloquentSpatial;
use MatanYadaev\EloquentSpatial\EloquentSpatialServiceProvider;
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

        // @phpstan-ignore-next-line
        if (version_compare(Application::VERSION, '11.0.0', '>=')) {
            $this->loadMigrationsFrom(__DIR__.'/database/migrations-laravel->=11');
        } else {
            $this->loadMigrationsFrom(__DIR__.'/database/migrations-laravel-<=10');
        }
    }

    /**
     * @return class-string<ServiceProvider>[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            EloquentSpatialServiceProvider::class,
        ];
    }

    protected function resetGeometryClasses(): void
    {
        EloquentSpatial::useGeometryCollection(GeometryCollection::class);
        EloquentSpatial::useLineString(LineString::class);
        EloquentSpatial::useMultiLineString(MultiLineString::class);
        EloquentSpatial::useMultiPoint(MultiPoint::class);
        EloquentSpatial::useMultiPolygon(MultiPolygon::class);
        EloquentSpatial::usePoint(Point::class);
        EloquentSpatial::usePolygon(Polygon::class);
    }
}
