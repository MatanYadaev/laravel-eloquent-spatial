<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Geometry;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends Builder<TModel>
 *
 * @mixin \Illuminate\Database\Query\Builder
 */
class SpatialBuilder extends Builder
{
  public function withDistance(
    ExpressionContract|Geometry|string $column,
    ExpressionContract|Geometry|string $geometryOrColumn,
    string $alias = 'distance'
  ): self {
    if (! $this->getQuery()->columns) {
      $this->select('*');
    }

    $this->selectRaw(
      sprintf(
        'ST_DISTANCE(%s, %s) AS %s',
        $this->toExpressionString($column),
        $this->toExpressionString($geometryOrColumn),
        $alias,
      )
    );

    return $this;
  }

  public function whereDistance(
    ExpressionContract|Geometry|string $column,
    ExpressionContract|Geometry|string $geometryOrColumn,
    string $operator,
    int|float $value
  ): self {
    $this->whereRaw(
      sprintf(
        'ST_DISTANCE(%s, %s) %s ?',
        $this->toExpressionString($column),
        $this->toExpressionString($geometryOrColumn),
        $operator,
      ),
      [$value],
    );

    return $this;
  }

  public function orderByDistance(
    ExpressionContract|Geometry|string $column,
    ExpressionContract|Geometry|string $geometryOrColumn,
    string $direction = 'asc'
  ): self {
    $this->orderByRaw(
      sprintf(
        'ST_DISTANCE(%s, %s) %s',
        $this->toExpressionString($column),
        $this->toExpressionString($geometryOrColumn),
        $direction,
      )
    );

    return $this;
  }

  public function withDistanceSphere(
    ExpressionContract|Geometry|string $column,
    ExpressionContract|Geometry|string $geometryOrColumn,
    string $alias = 'distance'
  ): self {
    if (! $this->getQuery()->columns) {
      $this->select('*');
    }

    $this->selectRaw(
      sprintf(
        'ST_DISTANCE_SPHERE(%s, %s) AS %s',
        $this->toExpressionString($column),
        $this->toExpressionString($geometryOrColumn),
        $alias,
      )
    );

    return $this;
  }

  public function whereDistanceSphere(
    ExpressionContract|Geometry|string $column,
    ExpressionContract|Geometry|string $geometryOrColumn,
    string $operator,
    int|float $value
  ): self {
    $this->whereRaw(
      sprintf(
        'ST_DISTANCE_SPHERE(%s, %s) %s ?',
        $this->toExpressionString($column),
        $this->toExpressionString($geometryOrColumn),
        $operator,
      ),
      [$value],
    );

    return $this;
  }

  public function orderByDistanceSphere(
    ExpressionContract|Geometry|string $column,
    ExpressionContract|Geometry|string $geometryOrColumn,
    string $direction = 'asc'
  ): self {
    $this->orderByRaw(
      sprintf(
        'ST_DISTANCE_SPHERE(%s, %s) %s',
        $this->toExpressionString($column),
        $this->toExpressionString($geometryOrColumn),
        $direction
      )
    );

    return $this;
  }

  public function whereWithin(
    ExpressionContract|Geometry|string $column,
    ExpressionContract|Geometry|string $geometryOrColumn,
  ): self {
    $this->whereRaw(
      sprintf(
        'ST_WITHIN(%s, %s)',
        $this->toExpressionString($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereNotWithin(
    ExpressionContract|Geometry|string $column,
    ExpressionContract|Geometry|string $geometryOrColumn,
  ): self {
    $this->whereRaw(
      sprintf(
        'ST_WITHIN(%s, %s) = 0',
        $this->toExpressionString($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereContains(
    ExpressionContract|Geometry|string $column,
    ExpressionContract|Geometry|string $geometryOrColumn,
  ): self {
    $this->whereRaw(
      sprintf(
        'ST_CONTAINS(%s, %s)',
        $this->toExpressionString($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereNotContains(
    ExpressionContract|Geometry|string $column,
    ExpressionContract|Geometry|string $geometryOrColumn,
  ): self {
    $this->whereRaw(
      sprintf(
        'ST_CONTAINS(%s, %s) = 0',
        $this->toExpressionString($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereTouches(
    ExpressionContract|Geometry|string $column,
    ExpressionContract|Geometry|string $geometryOrColumn,
  ): self {
    $this->whereRaw(
      sprintf(
        'ST_TOUCHES(%s, %s)',
        $this->toExpressionString($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereIntersects(
    ExpressionContract|Geometry|string $column,
    ExpressionContract|Geometry|string $geometryOrColumn,
  ): self {
    $this->whereRaw(
      sprintf(
        'ST_INTERSECTS(%s, %s)',
        $this->toExpressionString($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereCrosses(
    ExpressionContract|Geometry|string $column,
    ExpressionContract|Geometry|string $geometryOrColumn,
  ): self {
    $this->whereRaw(
      sprintf(
        'ST_CROSSES(%s, %s)',
        $this->toExpressionString($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereDisjoint(
    ExpressionContract|Geometry|string $column,
    ExpressionContract|Geometry|string $geometryOrColumn,
  ): self {
    $this->whereRaw(
      sprintf(
        'ST_DISJOINT(%s, %s)',
        $this->toExpressionString($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereOverlaps(
    ExpressionContract|Geometry|string $column,
    ExpressionContract|Geometry|string $geometryOrColumn,
  ): self {
    $this->whereRaw(
      sprintf(
        'ST_OVERLAPS(%s, %s)',
        $this->toExpressionString($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereEquals(
    ExpressionContract|Geometry|string $column,
    ExpressionContract|Geometry|string $geometryOrColumn,
  ): self {
    $this->whereRaw(
      sprintf(
        'ST_EQUALS(%s, %s)',
        $this->toExpressionString($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereSrid(
    ExpressionContract|Geometry|string $column,
    string $operator,
    int|float $value
  ): self {
    $this->whereRaw(
      sprintf(
        'ST_SRID(%s) %s ?',
        $this->toExpressionString($column),
        $operator,
      ),
      [$value],
    );

    return $this;
  }

  public function withCentroid(
    ExpressionContract|Geometry|string $column,
    string $alias = 'centroid',
  ): self {
    $this->selectRaw(
      sprintf(
        'ST_CENTROID(%s) AS %s',
        $this->toExpressionString($column),
        $this->getGrammar()->wrap($alias),
      )
    );

    return $this;
  }

  protected function toExpressionString(ExpressionContract|Geometry|string $geometryOrColumnOrExpression): string
  {
    $grammar = $this->getGrammar();

    if ($geometryOrColumnOrExpression instanceof ExpressionContract) {
      $expression = $geometryOrColumnOrExpression;
    } elseif ($geometryOrColumnOrExpression instanceof Geometry) {
      $expression = $geometryOrColumnOrExpression->toSqlExpression($this->getConnection());
    } else {
      $expression = DB::raw($grammar->wrap($geometryOrColumnOrExpression));
    }

    return (string) $expression->getValue($grammar);
  }
}
