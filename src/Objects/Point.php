<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Helper;

class Point extends Geometry
{
    public float $latitude;

    public float $longitude;

    public function __construct(float $latitude, float $longitude, int|Srid|null $srid = null)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->srid = Helper::getSrid($srid);
    }

    public function toWkt(): string
    {
        $wktData = $this->getWktData();

        return "POINT({$wktData})";
    }

    public function getWktData(): string
    {
        return "{$this->longitude} {$this->latitude}";
    }

    /**
     * @return array{0: float, 1: float}
     */
    public function getCoordinates(): array
    {
        return [
            $this->longitude,
            $this->latitude,
        ];
    }
}
