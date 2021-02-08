<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Builders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use MatanYadaev\EloquentSpatial\Objects\Geometry;

class SpatialBuilder extends Builder
{
    public function withDistance(
        string $column,
        Geometry | string $geometryOrColumn,
        string $alias = 'distance'
    ): self {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        if (! $this->getQuery()->columns) {
            $this->select('*');
        }

        return $this->selectRaw(
            sprintf(
                'ST_DISTANCE(%s, %s) AS %s',
                "`{$column}`",
                $geometryOrColumn,
                $alias,
            )
        );
    }

    public function whereDistance(
        string $column,
        Geometry | string $geometryOrColumn,
        string $operator,
        int | float $distance
    ): self {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        return $this->whereRaw(
            sprintf(
                'ST_DISTANCE(%s, %s) %s %s',
                "`{$column}`",
                $geometryOrColumn,
                $operator,
                $distance,
            )
        );
    }

    public function orderByDistance(
        string $column,
        Geometry | string $geometryOrColumn,
        string $direction = 'asc'
    ): self {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        return $this->orderByRaw(
            sprintf(
                'ST_DISTANCE(%s, %s) %s',
                "`{$column}`",
                $geometryOrColumn,
                $direction,
            )
        );
    }

    public function withDistanceSphere(
        string $column,
        Geometry | string $geometryOrColumn,
        string $alias = 'distance'
    ): self {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        if (! $this->getQuery()->columns) {
            $this->select('*');
        }

        return $this->selectRaw(
            sprintf(
                'ST_DISTANCE_SPHERE(%s, %s) AS %s',
                "`{$column}`",
                $geometryOrColumn,
                $alias,
            )
        );
    }

    public function whereDistanceSphere(
        string $column,
        Geometry | string $geometryOrColumn,
        string $operator,
        int | float $distance): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        return $this->whereRaw(
            sprintf(
                'ST_DISTANCE_SPHERE(%s, %s) %s %s',
                "`{$column}`",
                $geometryOrColumn,
                $operator,
                $distance
            )
        );
    }

    public function orderByDistanceSphere(
        string $column,
        Geometry | string $geometryOrColumn,
        string $direction = 'asc'
    ): self {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        return $this->orderByRaw(
            sprintf(
                'ST_DISTANCE_SPHERE(%s, %s) %s',
                "`{$column}`",
                $geometryOrColumn,
                $direction
            )
        );
    }

    public function whereWithin(string $column, Geometry | string $geometryOrColumn): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        return $this->whereRaw(
            sprintf(
                'ST_WITHIN(%s, %s)',
                "`{$column}`",
                $geometryOrColumn,
            )
        );
    }

    public function whereContains(string $column, Geometry | string $geometryOrColumn): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        return $this->whereRaw(
            sprintf(
                'ST_CONTAINS(%s, %s)',
                "`{$column}`",
                $geometryOrColumn,
            )
        );
    }

    public function whereTouches(string $column, Geometry | string $geometryOrColumn): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        return $this->whereRaw(
            sprintf(
                'ST_TOUCHES(%s, %s)',
                "`{$column}`",
                $geometryOrColumn,
            )
        );
    }

    public function whereIntersects(string $column, Geometry | string $geometryOrColumn): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        return $this->whereRaw(
            sprintf(
                'ST_INTERSECTS(%s, %s)',
                "`{$column}`",
                $geometryOrColumn,
            )
        );
    }

    public function whereCrosses(string $column, Geometry | string $geometryOrColumn): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        return $this->whereRaw(
            sprintf(
                'ST_CROSSES(%s, %s)',
                "`{$column}`",
                $geometryOrColumn,
            )
        );
    }

    public function whereDisjoint(string $column, Geometry | string $geometryOrColumn): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        return $this->whereRaw(
            sprintf(
                'ST_DISJOINT(%s, %s)',
                "`{$column}`",
                $geometryOrColumn,
            )
        );
    }

    public function whereOverlaps(string $column, Geometry | string $geometryOrColumn): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        return $this->whereRaw(
            sprintf(
                'ST_OVERLAPS(%s, %s)',
                "`{$column}`",
                $geometryOrColumn,
            )
        );
    }

    public function whereEquals(string $column, Geometry | string $geometryOrColumn): self
    {
        $geometryOrColumn = $this->toExpression($geometryOrColumn);

        return $this->whereRaw(
            sprintf(
                'ST_EQUALS(%s, %s)',
                "`{$column}`",
                $geometryOrColumn,
            )
        );
    }

    protected function toExpression(Geometry | string $geometryOrColumn): Expression
    {
        return $geometryOrColumn instanceof Geometry
            ? $geometryOrColumn->toWkt()
            : new Expression("`{$geometryOrColumn}`");
    }
}
