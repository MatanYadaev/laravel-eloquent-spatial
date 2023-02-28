<?php

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Exceptions\GeometryQueryException;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\Point;

class GeometryUtilities
{
  use SpatialQueryHelpers;

  public function __construct(protected ?string $connection = null)
  {

  }

  public static function make(string $connection = null): self
  {
    return new self($connection);
  }

  private function getGrammar(): Grammar
  {
    return DB::connection($this->connection)->getQueryGrammar();
  }

  private function getConnection(): \Illuminate\Database\Connection
  {
    return DB::connection($this->connection);
  }

  public function convexHull(
    ExpressionContract|Geometry|string $geometry,
  ): Geometry
  {
    $queryResult = DB::connection($this->connection)
      ->query()
      ->selectRaw(
        sprintf(
          'ST_CONVEXHULL(%s) as result',
          $this->toExpressionString($geometry),
        )
      )->first();

    throw_unless($queryResult, GeometryQueryException::noData());

    // @phpstan-ignore-next-line
    return Geometry::fromWkb($queryResult->result);
  }

  /**
   * Compute the distance between two points on a sphere in meters.
   *
   * @param  ExpressionContract|Point|string  $point1
   * @param  ExpressionContract|Point|string  $point2
   * @param  float|null  $sphereSize Size of the sphere
   * @return float Distance between points
   */
  public function distanceSphere(ExpressionContract|Point|string $point1, ExpressionContract|Point|string $point2, float $sphereSize = null): float
  {
    $queryResult = DB::connection($this->connection)
      ->query()
      ->when($sphereSize, fn ($query) => $query
        ->selectRaw(
          sprintf(
            'ST_DISTANCE_SPHERE(%s, %s, %s) as result',
            $this->toExpressionString($point1),
            $this->toExpressionString($point2),
            $sphereSize
          )
        ), fn ($query) => $query
        ->selectRaw(
          sprintf(
            'ST_DISTANCE_SPHERE(%s, %s) as result',
            $this->toExpressionString($point1),
            $this->toExpressionString($point2),
          )
        )
      )
      ->first();

    throw_unless($queryResult, GeometryQueryException::noData());

    return $queryResult->result;
  }
}
