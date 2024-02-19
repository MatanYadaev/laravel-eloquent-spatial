<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\PostgresConnection;

/** @codeCoverageIgnore */
class SpatialFunctionNormalizer
{
  public static function getDistanceSphereFunction(ConnectionInterface $connection): string
  {
    return $connection instanceof PostgresConnection
      ? 'ST_DistanceSphere'
      : 'ST_DISTANCE_SPHERE';
  }

  public static function normalizeGeometryExpression(ConnectionInterface $connection, string $expression): string
  {
    return $connection instanceof PostgresConnection
      ? $expression.'::geometry'
      : $expression;
  }
}
