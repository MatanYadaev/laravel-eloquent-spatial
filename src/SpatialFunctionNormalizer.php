<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\PostgresConnection;

class SpatialFunctionNormalizer
{
  public static function getDistanceSphereFunction(ConnectionInterface $connection): string
  {
    return $connection instanceof PostgresConnection
      ? 'ST_DistanceSphere'
      : 'ST_DISTANCE_SPHERE';
  }
}
