<?php

namespace MatanYadaev\EloquentSpatial\Traits;

use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\PostgresConnection;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\GeometryExpression;
use MatanYadaev\EloquentSpatial\Objects\Geometry;

trait HasSpatial
{
    public function originalIsEquivalent($key)
    {
        if (! array_key_exists($key, $this->original)) {
            return false;
        }

        $casts = $this->getCasts();

        if (array_key_exists($key, $casts)) {
            $original = $this->getOriginal($key);
            $attribute = $this->getAttributeValue($key);

            if ($original instanceof Geometry && $attribute instanceof Geometry) {
                return $original->getWktData() === $attribute->getWktData();
            }
        }

        return parent::originalIsEquivalent($key);
    }

    public function scopeWithDistance(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        ExpressionContract|Geometry|string $geometryOrColumn,
        string $alias = 'distance'
    ): void {
        if (! $query->getQuery()->columns) {
            $query->select('*');
        }

        $query->selectRaw(
            sprintf(
                'ST_DISTANCE(%s, %s) AS %s',
                $this->toExpressionString($column),
                $this->toExpressionString($geometryOrColumn),
                $alias,
            )
        );
    }

    public function scopeWhereDistance(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        ExpressionContract|Geometry|string $geometryOrColumn,
        string $operator,
        int|float $value
    ): void {
        $query->whereRaw(
            sprintf(
                'ST_DISTANCE(%s, %s) %s ?',
                $this->toExpressionString($column),
                $this->toExpressionString($geometryOrColumn),
                $operator,
            ),
            [$value],
        );
    }

    public function scopeOrderByDistance(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        ExpressionContract|Geometry|string $geometryOrColumn,
        string $direction = 'asc'
    ): void {
        $query->orderByRaw(
            sprintf(
                'ST_DISTANCE(%s, %s) %s',
                $this->toExpressionString($column),
                $this->toExpressionString($geometryOrColumn),
                $direction,
            )
        );
    }

    public function scopeWithDistanceSphere(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        ExpressionContract|Geometry|string $geometryOrColumn,
        string $alias = 'distance'
    ): void {
        if (! $query->getQuery()->columns) {
            $query->select('*');
        }

        // @codeCoverageIgnoreStart
        $function = $this->getConnection() instanceof PostgresConnection
          ? 'ST_DistanceSphere'
          : 'ST_DISTANCE_SPHERE';
        // @codeCoverageIgnoreEnd

        $query->selectRaw(
            sprintf(
                '%s(%s, %s) AS %s',
                $function,
                $this->toExpressionString($column),
                $this->toExpressionString($geometryOrColumn),
                $alias,
            )
        );
    }

    public function scopeWhereDistanceSphere(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        ExpressionContract|Geometry|string $geometryOrColumn,
        string $operator,
        int|float $value
    ): void {
        // @codeCoverageIgnoreStart
        $function = $this->getConnection() instanceof PostgresConnection
          ? 'ST_DistanceSphere'
          : 'ST_DISTANCE_SPHERE';
        // @codeCoverageIgnoreEnd

        $query->whereRaw(
            sprintf(
                '%s(%s, %s) %s ?',
                $function,
                $this->toExpressionString($column),
                $this->toExpressionString($geometryOrColumn),
                $operator,
            ),
            [$value],
        );
    }

    public function scopeOrderByDistanceSphere(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        ExpressionContract|Geometry|string $geometryOrColumn,
        string $direction = 'asc'
    ): void {
        // @codeCoverageIgnoreStart
        $function = $this->getConnection() instanceof PostgresConnection
          ? 'ST_DistanceSphere'
          : 'ST_DISTANCE_SPHERE';
        // @codeCoverageIgnoreEnd

        $query->orderByRaw(
            sprintf(
                '%s(%s, %s) %s',
                $function,
                $this->toExpressionString($column),
                $this->toExpressionString($geometryOrColumn),
                $direction
            )
        );
    }

    public function scopeWhereWithin(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        ExpressionContract|Geometry|string $geometryOrColumn,
    ): void {
        $query->whereRaw(
            sprintf(
                'ST_WITHIN(%s, %s)',
                $this->toExpressionString($column),
                $this->toExpressionString($geometryOrColumn),
            )
        );
    }

    public function scopeWhereNotWithin(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        ExpressionContract|Geometry|string $geometryOrColumn,
    ): void {
        $value = $this->getConnection() instanceof PostgresConnection ? 'false' : 0;

        $query->whereRaw(
            sprintf(
                'ST_WITHIN(%s, %s) = %s',
                $this->toExpressionString($column),
                $this->toExpressionString($geometryOrColumn),
                $value,
            )
        );
    }

    public function scopeWhereContains(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        ExpressionContract|Geometry|string $geometryOrColumn,
    ): void {
        $query->whereRaw(
            sprintf(
                'ST_CONTAINS(%s, %s)',
                $this->toExpressionString($column),
                $this->toExpressionString($geometryOrColumn),
            )
        );
    }

    public function scopeWhereNotContains(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        ExpressionContract|Geometry|string $geometryOrColumn,
    ): void {
        $value = $this->getConnection() instanceof PostgresConnection ? 'false' : 0;

        $query->whereRaw(
            sprintf(
                'ST_CONTAINS(%s, %s) = %s',
                $this->toExpressionString($column),
                $this->toExpressionString($geometryOrColumn),
                $value,
            )
        );
    }

    public function scopeWhereTouches(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        ExpressionContract|Geometry|string $geometryOrColumn,
    ): void {
        $query->whereRaw(
            sprintf(
                'ST_TOUCHES(%s, %s)',
                $this->toExpressionString($column),
                $this->toExpressionString($geometryOrColumn),
            )
        );
    }

    public function scopeWhereIntersects(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        ExpressionContract|Geometry|string $geometryOrColumn,
    ): void {
        $query->whereRaw(
            sprintf(
                'ST_INTERSECTS(%s, %s)',
                $this->toExpressionString($column),
                $this->toExpressionString($geometryOrColumn),
            )
        );
    }

    public function scopeWhereCrosses(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        ExpressionContract|Geometry|string $geometryOrColumn,
    ): void {
        $query->whereRaw(
            sprintf(
                'ST_CROSSES(%s, %s)',
                $this->toExpressionString($column),
                $this->toExpressionString($geometryOrColumn),
            )
        );
    }

    public function scopeWhereDisjoint(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        ExpressionContract|Geometry|string $geometryOrColumn,
    ): void {
        $query->whereRaw(
            sprintf(
                'ST_DISJOINT(%s, %s)',
                $this->toExpressionString($column),
                $this->toExpressionString($geometryOrColumn),
            )
        );
    }

    public function scopeWhereOverlaps(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        ExpressionContract|Geometry|string $geometryOrColumn,
    ): void {
        $query->whereRaw(
            sprintf(
                'ST_OVERLAPS(%s, %s)',
                $this->toExpressionString($column),
                $this->toExpressionString($geometryOrColumn),
            )
        );
    }

    public function scopeWhereEquals(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        ExpressionContract|Geometry|string $geometryOrColumn,
    ): void {
        $query->whereRaw(
            sprintf(
                'ST_EQUALS(%s, %s)',
                $this->toExpressionString($column),
                $this->toExpressionString($geometryOrColumn),
            )
        );
    }

    public function scopeWhereSrid(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        string $operator,
        int|float $value
    ): void {
        $query->whereRaw(
            sprintf(
                'ST_SRID(%s) %s ?',
                $this->toExpressionString($column),
                $operator,
            ),
            [$value],
        );
    }

    public function scopeWithCentroid(
        Builder $query,
        ExpressionContract|Geometry|string $column,
        string $alias = 'centroid',
    ): void {
        $query->selectRaw(
            sprintf(
                'ST_CENTROID(%s) AS %s',
                $this->toExpressionString($column),
                $query->getGrammar()->wrap($alias),
            )
        );
    }

    protected function toExpressionString(ExpressionContract|Geometry|string $geometryOrColumnOrExpression): string
    {
        $grammar = $this->getGrammar();

        if ($geometryOrColumnOrExpression instanceof ExpressionContract) {
            $expression = $geometryOrColumnOrExpression;
        } elseif ($geometryOrColumnOrExpression instanceof Geometry) {
            $expression = DB::raw($geometryOrColumnOrExpression->toSqlExpression($this->getConnection())->getValue($grammar));
        } else {
            $expression = DB::raw(
                (new GeometryExpression($grammar->wrap($geometryOrColumnOrExpression)))->normalize($this->getConnection())
            );
        }

        return (string) $expression->getValue($grammar);
    }
}
