<?php

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

class Polygon extends MultiLineString
{
    public function toWkt(): Expression
    {
        $collectionWkt = $this->toCollectionWkt();
        $expression = DB::raw("POLYGON({$collectionWkt})");

        if ($this->srid) {
            $expression = DB::raw("ST_SRID({$expression}, {$this->srid})");
        }

        return $expression;
    }
}
