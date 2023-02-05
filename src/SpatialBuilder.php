<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
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
    Expression|Geometry|string $column,
    Expression|Geometry|string $geometryOrColumn,
    string $alias = 'distance'
  ): self
  {
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
    Expression|Geometry|string $column,
    Expression|Geometry|string $geometryOrColumn,
    string $operator,
    int|float $value
  ): self
  {
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
    Expression|Geometry|string $column,
    Expression|Geometry|string $geometryOrColumn,
    string $direction = 'asc'
  ): self
  {
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
    Expression|Geometry|string $column,
    Expression|Geometry|string $geometryOrColumn,
    string $alias = 'distance'
  ): self
  {
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
    Expression|Geometry|string $column,
    Expression|Geometry|string $geometryOrColumn,
    string $operator,
    int|float $value
  ): self
  {
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
    Expression|Geometry|string $column,
    Expression|Geometry|string $geometryOrColumn,
    string $direction = 'asc'
  ): self
  {
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
    Expression|Geometry|string $column,
    Expression|Geometry|string $geometryOrColumn,
  ): self
  {
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
    Expression|Geometry|string $column,
    Expression|Geometry|string $geometryOrColumn,
  ): self
  {
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
    Expression|Geometry|string $column,
    Expression|Geometry|string $geometryOrColumn,
  ): self
  {
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
    Expression|Geometry|string $column,
    Expression|Geometry|string $geometryOrColumn,
  ): self
  {
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
    Expression|Geometry|string $column,
    Expression|Geometry|string $geometryOrColumn,
  ): self
  {
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
    Expression|Geometry|string $column,
    Expression|Geometry|string $geometryOrColumn,
  ): self
  {
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
    Expression|Geometry|string $column,
    Expression|Geometry|string $geometryOrColumn,
  ): self
  {
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
    Expression|Geometry|string $column,
    Expression|Geometry|string $geometryOrColumn,
  ): self
  {
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
    Expression|Geometry|string $column,
    Expression|Geometry|string $geometryOrColumn,
  ): self
  {
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
    Expression|Geometry|string $column,
    Expression|Geometry|string $geometryOrColumn,
  ): self
  {
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
    Expression|Geometry|string $column,
    string $operator,
    int|float $value
  ): self
  {
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

  protected function toExpressionString(Expression|Geometry|string $geometryOrColumnOrExpression): string
  {
    $grammar = $this->getQuery()->getGrammar();

    if ($geometryOrColumnOrExpression instanceof Expression) {
      $expression = $geometryOrColumnOrExpression;
    } else if ($geometryOrColumnOrExpression instanceof Geometry) {
      $expression = $geometryOrColumnOrExpression->toSqlExpression($this->getConnection());
    } else {
      $expression = DB::raw($grammar->wrap($geometryOrColumnOrExpression));
    }

    return (string) $expression->getValue($grammar);
  }
}
