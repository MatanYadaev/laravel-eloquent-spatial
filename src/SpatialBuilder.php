<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

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
    string $column,
    Geometry|string $geometryOrColumn,
    string $alias = 'distance'
  ): self
  {
    if (! $this->getQuery()->columns) {
      $this->select('*');
    }

    $grammar = $this->query->grammar;

    $this->selectRaw(
      sprintf(
        'ST_DISTANCE(%s, %s) AS %s',
        $grammar->wrap($column),
        $this->toExpressionString($geometryOrColumn),
        $alias,
      )
    );

    return $this;
  }

  public function whereDistance(
    string $column,
    Geometry|string $geometryOrColumn,
    string $operator,
    int|float $value
  ): self
  {
    $grammar = $this->query->grammar;

    $this->whereRaw(
      sprintf(
        'ST_DISTANCE(%s, %s) %s ?',
        $grammar->wrap($column),
        $this->toExpressionString($geometryOrColumn),
        $operator,
      ),
      [$value],
    );

    return $this;
  }

  public function orderByDistance(
    string $column,
    Geometry|string $geometryOrColumn,
    string $direction = 'asc'
  ): self
  {
    $grammar = $this->query->grammar;

    $this->orderByRaw(
      sprintf(
        'ST_DISTANCE(%s, %s) %s',
        $grammar->wrap($column),
        $this->toExpressionString($geometryOrColumn),
        $direction,
      )
    );

    return $this;
  }

  public function withDistanceSphere(
    string $column,
    Geometry|string $geometryOrColumn,
    string $alias = 'distance'
  ): self
  {
    if (! $this->getQuery()->columns) {
      $this->select('*');
    }

    $grammar = $this->query->grammar;

    $this->selectRaw(
      sprintf(
        'ST_DISTANCE_SPHERE(%s, %s) AS %s',
        $grammar->wrap($column),
        $this->toExpressionString($geometryOrColumn),
        $alias,
      )
    );

    return $this;
  }

  public function whereDistanceSphere(
    string $column,
    Geometry|string $geometryOrColumn,
    string $operator,
    int|float $value
  ): self
  {
    $grammar = $this->query->grammar;

    $this->whereRaw(
      sprintf(
        'ST_DISTANCE_SPHERE(%s, %s) %s ?',
        $grammar->wrap($column),
        $this->toExpressionString($geometryOrColumn),
        $operator,
      ),
      [$value],
    );

    return $this;
  }

  public function orderByDistanceSphere(
    string $column,
    Geometry|string $geometryOrColumn,
    string $direction = 'asc'
  ): self
  {
    $grammar = $this->query->grammar;

    $this->orderByRaw(
      sprintf(
        'ST_DISTANCE_SPHERE(%s, %s) %s',
        $grammar->wrap($column),
        $this->toExpressionString($geometryOrColumn),
        $direction
      )
    );

    return $this;
  }

  public function whereWithin(string $column, Geometry|string $geometryOrColumn): self
  {
    $grammar = $this->query->grammar;

    $this->whereRaw(
      sprintf(
        'ST_WITHIN(%s, %s)',
        $grammar->wrap($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereNotWithin(string $column, Geometry|string $geometryOrColumn): self
  {
    $grammar = $this->query->grammar;

    $this->whereRaw(
      sprintf(
        'ST_WITHIN(%s, %s) = 0',
        $grammar->wrap($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereContains(string $column, Geometry|string $geometryOrColumn): self
  {
    $grammar = $this->query->grammar;

    $this->whereRaw(
      sprintf(
        'ST_CONTAINS(%s, %s)',
        $grammar->wrap($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereNotContains(string $column, Geometry|string $geometryOrColumn): self
  {
    $grammar = $this->query->grammar;

    $this->whereRaw(
      sprintf(
        'ST_CONTAINS(%s, %s) = 0',
        $grammar->wrap($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereTouches(string $column, Geometry|string $geometryOrColumn): self
  {
    $grammar = $this->query->grammar;

    $this->whereRaw(
      sprintf(
        'ST_TOUCHES(%s, %s)',
        $grammar->wrap($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereIntersects(string $column, Geometry|string $geometryOrColumn): self
  {
    $grammar = $this->getQuery()->getGrammar();

    $this->whereRaw(
      sprintf(
        'ST_INTERSECTS(%s, %s)',
        $grammar->wrap($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereCrosses(string $column, Geometry|string $geometryOrColumn): self
  {
    $grammar = $this->query->grammar;

    $this->whereRaw(
      sprintf(
        'ST_CROSSES(%s, %s)',
        $grammar->wrap($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereDisjoint(string $column, Geometry|string $geometryOrColumn): self
  {
    $grammar = $this->query->grammar;

    $this->whereRaw(
      sprintf(
        'ST_DISJOINT(%s, %s)',
        $grammar->wrap($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereOverlaps(string $column, Geometry|string $geometryOrColumn): self
  {
    $grammar = $this->query->grammar;

    $this->whereRaw(
      sprintf(
        'ST_OVERLAPS(%s, %s)',
        $grammar->wrap($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereEquals(string $column, Geometry|string $geometryOrColumn): self
  {
    $grammar = $this->query->grammar;

    $this->whereRaw(
      sprintf(
        'ST_EQUALS(%s, %s)',
        $grammar->wrap($column),
        $this->toExpressionString($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereSrid(
    string $column,
    string $operator,
    int|float $value
  ): self
  {
    $grammar = $this->query->grammar;

    $this->whereRaw(
      sprintf(
        'ST_SRID(%s) %s ?',
        $grammar->wrap($column),
        $operator,
      ),
      [$value],
    );

    return $this;
  }

  protected function toExpressionString(Geometry|string $geometryOrColumn): string
  {
    $grammar = $this->getQuery()->getGrammar();

    if ($geometryOrColumn instanceof Geometry) {
      $expression = $geometryOrColumn->toSqlExpression($this->getConnection());
    } else {
      $expression = DB::raw($grammar->wrap($geometryOrColumn));
    }

    return Expression::getValue($expression, $grammar);
  }
}
