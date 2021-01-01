<?php

namespace MatanYadaev\EloquentSpatial\Builders;

use DB;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use MatanYadaev\EloquentSpatial\Objects\Geometry;

class SpatialBuilder extends Builder
{
    public function withDistance(
        Geometry | string $columnOrGeometry,
        Geometry | string $columnOrGeometry2,
        string $as = 'distance',
    ): self {
        $dbDriver = $this->getDbDriver();
        $columnOrGeometry = $columnOrGeometry instanceof Geometry ? $columnOrGeometry->toWkt($dbDriver) : $columnOrGeometry;
        $columnOrGeometry2 = $columnOrGeometry2 instanceof Geometry ? $columnOrGeometry2->toWkt($dbDriver) : $columnOrGeometry2;

        // @TODO Is that necessary?
        if (!$this->getQuery()->columns) {
            $this->select('*');
        }

        $this->selectRaw("ST_DISTANCE({$columnOrGeometry}, {$columnOrGeometry2}) AS {$as}");

        return $this;
    }

    public function withDistanceSphere(
        Geometry | string $columnOrGeometry,
        Geometry | string $columnOrGeometry2,
        string $as = 'distance',
    ): self {
        $dbDriver = $this->getDbDriver();
        $columnOrGeometry = $columnOrGeometry instanceof Geometry ? $columnOrGeometry->toWkt($dbDriver) : $columnOrGeometry;
        $columnOrGeometry2 = $columnOrGeometry2 instanceof Geometry ? $columnOrGeometry2->toWkt($dbDriver) : $columnOrGeometry2;

        // @TODO Is that necessary?
        if (!$this->getQuery()->columns) {
            $this->select('*');
        }

        $this->selectRaw("ST_DISTANCE_SPHERE({$columnOrGeometry}, {$columnOrGeometry2}) AS {$as}");

        return $this;
    }

    private function getDbDriver(): string
    {
        /** @var Connection $connection */
        $connection = $this->getConnection();

        return $connection->getDriverName();
    }
}
