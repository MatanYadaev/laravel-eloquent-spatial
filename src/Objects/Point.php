<?php

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

class Point extends Geometry
{
    public float $latitude;

    public float $longitude;

    public function __construct(float $latitude, float $longitude, ?int $srid = 0)
    {
        parent::__construct($srid);
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function toWkt(): Expression
    {
        $expression = DB::raw("POINT({$this->longitude}, {$this->latitude})");

        if ($this->srid) {
            $expression = DB::raw("ST_SRID({$expression}, {$this->srid})");
        }

        return $expression;
    }
}
