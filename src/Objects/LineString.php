<?php

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

class LineString extends PointCollection
{
    protected int $minimumGeometries = 2;

    public function toWkt(): Expression
    {
        return DB::raw("LINESTRING({$this->toCollectionWkt()})");
    }
}
