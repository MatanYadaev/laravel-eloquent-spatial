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

        return $this->selectRaw("ST_DISTANCE(`{$column}`, {$geometryOrColumn}) AS {$as}");
    }

    public function whereDistance(string $column, Geometry | string $geometryOrColumn, string $operator, int|float $distance): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        $this->whereRaw("ST_DISTANCE(`{$column}`, {$geometryOrColumn}) {$operator} $distance");

        return $this;
    }

    public function orderByDistance(string $column, Geometry | string $geometryOrColumn, string $direction = 'asc'): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        $this->orderByRaw("ST_DISTANCE(`{$column}`, {$geometryOrColumn}) {$direction}");

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

        return $this->whereRaw("ST_DISTANCE_SPHERE(`{$column}`, {$geometryOrColumn}) {$operator} {$distance}");
    }

    public function orderByDistanceSphere(string $column, Geometry | string $geometryOrColumn, string $direction = 'asc'): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        return $this->orderByRaw("ST_DISTANCE_SPHERE(`{$column}`, {$geometryOrColumn}) {$direction}");
    }

    public function whereWithin(string $column, Geometry | string $geometryOrColumn): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        return $this->whereRaw("ST_WITHIN(`{$column}`, {$geometryOrColumn})");
    }

    public function whereContains(string $column, Geometry | string $geometryOrColumn): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        return $this->whereRaw("ST_CONTAINS(`{$column}`, {$geometryOrColumn})");
    }

    public function whereTouches(string $column, Geometry | string $geometryOrColumn): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        return $this->whereRaw("ST_TOUCHES(`{$column}`, {$geometryOrColumn})");
    }

    protected function toExpression(Geometry | string $geometryOrColumn): Expression
    {
        return $geometryOrColumn instanceof Geometry
            ? $geometryOrColumn->toWkt()
            : new Expression("`{$geometryOrColumn}`");
    }
}
