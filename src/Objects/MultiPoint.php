<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

class MultiPoint extends PointCollection
{
    protected int $minimumGeometries = 1;

    public function toWkt(): Expression
    {
        return DB::raw("MULTIPOINT({$this->toCollectionWkt()})");
    }
}
