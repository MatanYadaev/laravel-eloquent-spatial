<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use MatanYadaev\EloquentSpatial\Objects\Geometry;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 * @extends Builder<TModel>
 */
class SpatialBuilder extends Builder
{
    public function withDistance(
        string $column,
        Geometry|string $geometryOrColumn,
        string $alias = 'distance'
    ): self {
        if (! $this->getQuery()->columns) {
            $this->select('*');
        }

        return $this->selectRaw(
            sprintf(
                'ST_DISTANCE(%s, %s) AS %s',
                "`{$column}`",
                $this->toExpression($geometryOrColumn),
                $alias,
            )
        );
    }

    public function whereDistance(
        string $column,
        Geometry|string $geometryOrColumn,
        string $operator,
        int|float $value
    ): self {
        return $this->whereRaw(
            sprintf(
                'ST_DISTANCE(%s, %s) %s %s',
                "`{$column}`",
                $this->toExpression($geometryOrColumn),
                $operator,
                $value,
            )
        );
    }

    public function orderByDistance(
        string $column,
        Geometry|string $geometryOrColumn,
        string $direction = 'asc'
    ): self {
        return $this->orderByRaw(
            sprintf(
                'ST_DISTANCE(%s, %s) %s',
                "`{$column}`",
                $this->toExpression($geometryOrColumn),
                $direction,
            )
        );
    }

    public function withDistanceSphere(
        string $column,
        Geometry|string $geometryOrColumn,
        string $alias = 'distance'
    ): self {
        if (! $this->getQuery()->columns) {
            $this->select('*');
        }

        return $this->selectRaw(
            sprintf(
                'ST_DISTANCE_SPHERE(%s, %s) AS %s',
                "`{$column}`",
                $this->toExpression($geometryOrColumn),
                $alias,
            )
        );
    }

    public function whereDistanceSphere(
        string $column,
        Geometry|string $geometryOrColumn,
        string $operator,
        int|float $value
    ): self {
        return $this->whereRaw(
            sprintf(
                'ST_DISTANCE_SPHERE(%s, %s) %s %s',
                "`{$column}`",
                $this->toExpression($geometryOrColumn),
                $operator,
                $value
            )
        );
    }

    public function orderByDistanceSphere(
        string $column,
        Geometry|string $geometryOrColumn,
        string $direction = 'asc'
    ): self {
        return $this->orderByRaw(
            sprintf(
                'ST_DISTANCE_SPHERE(%s, %s) %s',
                "`{$column}`",
                $this->toExpression($geometryOrColumn),
                $direction
            )
        );
    }

    public function whereWithin(string $column, Geometry|string $geometryOrColumn): self
    {
        return $this->whereRaw(
            sprintf(
                'ST_WITHIN(%s, %s)',
                "`{$column}`",
                $this->toExpression($geometryOrColumn),
            )
        );
    }

    public function whereContains(string $column, Geometry|string $geometryOrColumn): self
    {
        return $this->whereRaw(
            sprintf(
                'ST_CONTAINS(%s, %s)',
                "`{$column}`",
                $this->toExpression($geometryOrColumn),
            )
        );
    }

    public function whereTouches(string $column, Geometry|string $geometryOrColumn): self
    {
        return $this->whereRaw(
            sprintf(
                'ST_TOUCHES(%s, %s)',
                "`{$column}`",
                $this->toExpression($geometryOrColumn),
            )
        );
    }

    public function whereIntersects(string $column, Geometry|string $geometryOrColumn): self
    {
        return $this->whereRaw(
            sprintf(
                'ST_INTERSECTS(%s, %s)',
                "`{$column}`",
                $this->toExpression($geometryOrColumn),
            )
        );
    }

    public function whereCrosses(string $column, Geometry|string $geometryOrColumn): self
    {
        return $this->whereRaw(
            sprintf(
                'ST_CROSSES(%s, %s)',
                "`{$column}`",
                $this->toExpression($geometryOrColumn),
            )
        );
    }

    public function whereDisjoint(string $column, Geometry|string $geometryOrColumn): self
    {
        return $this->whereRaw(
            sprintf(
                'ST_DISJOINT(%s, %s)',
                "`{$column}`",
                $this->toExpression($geometryOrColumn),
            )
        );
    }

    public function whereOverlaps(string $column, Geometry|string $geometryOrColumn): self
    {
        return $this->whereRaw(
            sprintf(
                'ST_OVERLAPS(%s, %s)',
                "`{$column}`",
                $this->toExpression($geometryOrColumn),
            )
        );
    }

    public function whereEquals(string $column, Geometry|string $geometryOrColumn): self
    {
        return $this->whereRaw(
            sprintf(
                'ST_EQUALS(%s, %s)',
                "`{$column}`",
                $this->toExpression($geometryOrColumn),
            )
        );
    }

    protected function toExpression(Geometry|string $geometryOrColumn): Expression
    {
        return $geometryOrColumn instanceof Geometry
            ? $geometryOrColumn->toWkt()
            : new Expression("`{$geometryOrColumn}`");
    }
}
