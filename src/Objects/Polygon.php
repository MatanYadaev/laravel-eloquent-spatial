<?php

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

class Polygon extends MultiLineString
{
    public function toWkt(): Expression
    {
        return DB::raw("POLYGON({$this->toCollectionWkt()})");
    }
}
