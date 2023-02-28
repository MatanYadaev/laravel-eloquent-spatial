<?php

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Objects\Geometry;

trait SpatialQueryHelpers
{
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
