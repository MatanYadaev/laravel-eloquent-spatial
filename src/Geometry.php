<?php

namespace MatanYadaev\EloquentSpatial;

class Geometry
{
    public ?int $srid;

    public function __construct(?int $srid = 0)
    {
        $this->srid = (int) $srid;
    }
}
