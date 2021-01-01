<?php

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Database\Query\Expression;

abstract class Geometry
{
    public ?int $srid;

    public function __construct(?int $srid = 0)
    {
        $this->srid = (int) $srid;
    }

    abstract public function toWkt(string $dbDriver): Expression|string;
}
