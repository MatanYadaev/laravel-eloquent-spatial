<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

class LineString extends PointCollection
{
    protected int $minimumGeometries = 2;

    public function toWkt(bool $withFunction): string
    {
        $wkt = $this->toCollectionWkt(withFunction: false);

        if ($withFunction) {
            return "LINESTRING(${wkt})";
        }

        return "(${wkt})";
    }
}
