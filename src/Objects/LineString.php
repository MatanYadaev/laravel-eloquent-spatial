<?php

declare(strict_types=1);

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

    /**
     * @return array<Point>
     */
    public function getGeometries(): array
    {
        return parent::getGeometries();
    }
}
