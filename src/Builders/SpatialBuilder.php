<?php

namespace MatanYadaev\EloquentSpatial\Builders;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use MatanYadaev\EloquentSpatial\Objects\Geometry;

class SpatialBuilder extends Builder
{
    public function withDistance(string $column, Geometry | string $geometryOrColumn, string $as = 'distance'): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        if (! $this->getQuery()->columns) {
            $this->select('*');
        }

        $this->selectRaw("ST_DISTANCE(`{$column}`, {$geometryOrColumn}) AS {$as}");

        return $this;
    }

    public function whereDistance(string $column, Geometry | string $geometryOrColumn, string $operator, int|float $distance): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        $this->whereRaw("ST_DISTANCE(`{$column}`, {$geometryOrColumn}) {$operator} $distance");

        return $this;
    }

    public function withDistanceSphere(string $column, Geometry | string $geometryOrColumn, string $as = 'distance'): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        if (! $this->getQuery()->columns) {
            $this->select('*');
        }

        $this->selectRaw("ST_DISTANCE_SPHERE(`{$column}`, {$geometryOrColumn}) AS {$as}");

        return $this;
    }

    public function whereDistanceSphere(string $column, Geometry | string $geometryOrColumn, string $operator, int|float $distance): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        $this->whereRaw("ST_DISTANCE_SPHERE(`{$column}`, {$geometryOrColumn}) {$operator} {$distance}");

        return $this;
    }

    protected function toExpression(Geometry | string $geometryOrColumn): Expression
    {
        return $geometryOrColumn instanceof Geometry
            ? $geometryOrColumn->toWkt()
            : new Expression("`{$geometryOrColumn}`");
    }
}
