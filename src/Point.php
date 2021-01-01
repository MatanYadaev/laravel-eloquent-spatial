<?php

namespace MatanYadaev\EloquentSpatial;

class Point
{
    public float $latitude;

    public float $longitude;

    public ?int $srid;

    public function __construct(float $latitude, float $longitude, ?int $srid = null)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->srid = $srid;
    }
}
