<?php

namespace MatanYadaev\EloquentSpatial\Builders;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use MatanYadaev\EloquentSpatial\Objects\Geometry;

class SpatialBuilder extends Builder
{
    public function withDistance(string $column, Geometry | string $columnOrGeometry, string $as = 'distance'): self
    {
        $dbDriver = $this->getDbDriver();
        $columnOrGeometry = $columnOrGeometry instanceof Geometry ? $columnOrGeometry->toWkt($dbDriver) : "`{$columnOrGeometry}`";

        // @TODO Is that necessary?
        if (! $this->getQuery()->columns) {
            $this->select('*');
        }

        $this->selectRaw("ST_DISTANCE(`{$column}`, {$columnOrGeometry}) AS {$as}");

        return $this;
    }

    public function withDistanceSphere(string $column, Geometry | string $columnOrGeometry, string $as = 'distance'): self
    {
        $dbDriver = $this->getDbDriver();
        $columnOrGeometry = $columnOrGeometry instanceof Geometry
            ? $columnOrGeometry->toWkt($dbDriver)
            : "`{$columnOrGeometry}`";

        // @TODO Is that necessary?
        if (! $this->getQuery()->columns) {
            $this->select('*');
        }

        $this->selectRaw("ST_DISTANCE_SPHERE(`{$column}`, {$columnOrGeometry}) AS {$as}");

        return $this;
    }

    private function getDbDriver(): string
    {
        /** @var Connection $connection */
        $connection = $this->getConnection();

        return $connection->getDriverName();
    }
}
