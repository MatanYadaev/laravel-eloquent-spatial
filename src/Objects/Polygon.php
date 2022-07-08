<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

class Polygon extends MultiLineString
{
    public function toWkt(bool $withFunction): string
    {
        $wkt = $this->toCollectionWkt(withFunction: false);

        if ($withFunction) {
            return "POLYGON({$wkt})";
        }

        return "(${wkt})";
    }
}
