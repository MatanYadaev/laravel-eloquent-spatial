<?php

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Geometry;

class SpatialQuery extends Builder
{
  use SpatialQueryHelpers;

  public function __construct(ConnectionInterface $connection = null, Grammar $grammar = null, Processor $processor = null)
  {
    parent::__construct($connection ?? DB::connection(), $grammar, $processor);
  }

  public static function make(ConnectionInterface $connection = null, Grammar $grammar = null, Processor $processor = null): self
  {
    return new self($connection, $grammar, $processor);
  }

  public function convexHull(
    ExpressionContract|Geometry|string $geometry,
  ): self
  {
    $this->selectRaw(
      sprintf(
        'ST_CONVEXHULL(%s) as convex_hull',
        $this->toExpressionString($geometry),
      )
    );

    return $this;
  }
}
