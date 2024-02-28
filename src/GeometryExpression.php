<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\PostgresConnection;

/** @codeCoverageIgnore */
class GeometryExpression
{
    public function __construct(readonly private string $expression)
    {
    }

    public function normalize(ConnectionInterface $connection): string
    {
        return $connection instanceof PostgresConnection
          ? $this->expression.'::geometry'
          : $this->expression;
    }
}
