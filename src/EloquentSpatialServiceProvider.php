<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Database\Connection;
use Illuminate\Database\DatabaseServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Doctrine\GeographyType;
use MatanYadaev\EloquentSpatial\Doctrine\GeometryCollectionType;
use MatanYadaev\EloquentSpatial\Doctrine\GeometryType;
use MatanYadaev\EloquentSpatial\Doctrine\LineStringType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiLineStringType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiPointType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiPolygonType;
use MatanYadaev\EloquentSpatial\Doctrine\PointType;
use MatanYadaev\EloquentSpatial\Doctrine\PolygonType;

class EloquentSpatialServiceProvider extends DatabaseServiceProvider
{
    // @codeCoverageIgnoreStart
    public function boot(): void
    {
        if (version_compare(Application::VERSION, '11.0.0', '>=')) {
            return;
        }

        /** @var Connection $connection */
        $connection = DB::connection();

        if ($connection->isDoctrineAvailable()) {
            $this->registerDoctrineTypes($connection);
        }
    }

    protected function registerDoctrineTypes(Connection $connection): void
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
            'geography' => GeographyType::class,
            'geometry' => GeometryType::class,
        ];

        foreach ($geometries as $type => $class) {
            DB::registerDoctrineType($class, $type, $type);
            $connection->registerDoctrineType($class, $type, $type);
        }
    }
    // @codeCoverageIgnoreEnd
}
