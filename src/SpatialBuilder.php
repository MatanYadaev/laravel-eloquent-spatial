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
    string $column,
    Geometry|string $geometryOrColumn,
    string $alias = 'distance'
  ): self
  {
    if (! $this->getQuery()->columns) {
      $this->select('*');
    }

    $this->selectRaw(
      sprintf(
        'ST_DISTANCE(%s, %s) AS %s',
        $this->getQuery()->getGrammar()->wrap($column),
        $this->toExpression($geometryOrColumn),
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
    $this->whereRaw(
      sprintf(
        'ST_DISTANCE(%s, %s) %s ?',
        $this->getQuery()->getGrammar()->wrap($column),
        $this->toExpression($geometryOrColumn),
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
    $this->orderByRaw(
      sprintf(
        'ST_DISTANCE(%s, %s) %s',
        $this->getQuery()->getGrammar()->wrap($column),
        $this->toExpression($geometryOrColumn),
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

    $this->selectRaw(
      sprintf(
        'ST_DISTANCE_SPHERE(%s, %s) AS %s',
        $this->getQuery()->getGrammar()->wrap($column),
        $this->toExpression($geometryOrColumn),
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
    $this->whereRaw(
      sprintf(
        'ST_DISTANCE_SPHERE(%s, %s) %s ?',
        $this->getQuery()->getGrammar()->wrap($column),
        $this->toExpression($geometryOrColumn),
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
    $this->orderByRaw(
      sprintf(
        'ST_DISTANCE_SPHERE(%s, %s) %s',
        $this->getQuery()->getGrammar()->wrap($column),
        $this->toExpression($geometryOrColumn),
        $direction
      )
    );

    return $this;
  }

  public function whereWithin(string $column, Geometry|string $geometryOrColumn): self
  {
    $this->whereRaw(
      sprintf(
        'ST_WITHIN(%s, %s)',
        $this->getQuery()->getGrammar()->wrap($column),
        $this->toExpression($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereNotWithin(string $column, Geometry|string $geometryOrColumn): self
  {
    $this->whereRaw(
      sprintf(
        'ST_WITHIN(%s, %s) = 0',
        $this->getQuery()->getGrammar()->wrap($column),
        $this->toExpression($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereContains(string $column, Geometry|string $geometryOrColumn): self
  {
    $this->whereRaw(
      sprintf(
        'ST_CONTAINS(%s, %s)',
        $this->getQuery()->getGrammar()->wrap($column),
        $this->toExpression($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereNotContains(string $column, Geometry|string $geometryOrColumn): self
  {
    $this->whereRaw(
      sprintf(
        'ST_CONTAINS(%s, %s) = 0',
        $this->getQuery()->getGrammar()->wrap($column),
        $this->toExpression($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereTouches(string $column, Geometry|string $geometryOrColumn): self
  {
    $this->whereRaw(
      sprintf(
        'ST_TOUCHES(%s, %s)',
        $this->getQuery()->getGrammar()->wrap($column),
        $this->toExpression($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereIntersects(string $column, Geometry|string $geometryOrColumn): self
  {
    $this->whereRaw(
      sprintf(
        'ST_INTERSECTS(%s, %s)',
        $this->getQuery()->getGrammar()->wrap($column),
        $this->toExpression($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereCrosses(string $column, Geometry|string $geometryOrColumn): self
  {
    $this->whereRaw(
      sprintf(
        'ST_CROSSES(%s, %s)',
        $this->getQuery()->getGrammar()->wrap($column),
        $this->toExpression($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereDisjoint(string $column, Geometry|string $geometryOrColumn): self
  {
    $this->whereRaw(
      sprintf(
        'ST_DISJOINT(%s, %s)',
        $this->getQuery()->getGrammar()->wrap($column),
        $this->toExpression($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereOverlaps(string $column, Geometry|string $geometryOrColumn): self
  {
    $this->whereRaw(
      sprintf(
        'ST_OVERLAPS(%s, %s)',
        $this->getQuery()->getGrammar()->wrap($column),
        $this->toExpression($geometryOrColumn),
      )
    );

    return $this;
  }

  public function whereEquals(string $column, Geometry|string $geometryOrColumn): self
  {
    $this->whereRaw(
      sprintf(
        'ST_EQUALS(%s, %s)',
        $this->getQuery()->getGrammar()->wrap($column),
        $this->toExpression($geometryOrColumn),
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
    $this->whereRaw(
      sprintf(
        'ST_SRID(%s) %s ?',
        $this->getQuery()->getGrammar()->wrap($column),
        $operator,
      ),
      [$value],
    );

    return $this;
  }

  protected function toExpression(Geometry|string $geometryOrColumn): Expression
  {
    if ($geometryOrColumn instanceof Geometry) {
      return $geometryOrColumn->toSqlExpression($this->getConnection());
    }

    return DB::raw($this->getQuery()->getGrammar()->wrap($geometryOrColumn));
  }
}
