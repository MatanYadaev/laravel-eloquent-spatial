<?php

namespace MatanYadaev\EloquentSpatial;

use GeoIO\WKB\Parser\Parser as GeoIOParser;
use MatanYadaev\EloquentSpatial\Objects\Geometry;

class Parser
{
    public static function parse(string $wkb): Geometry
    {
        $srid = substr($wkb, 0, 4);
        $srid = unpack('L', $srid)[1];

        // MySQL adds 4 NULL bytes at the start of the binary
        $wkb = substr($wkb, 4);

        $geometry = (new GeoIOParser(
            new Factory(),
        ))->parse($wkb);

        $geometry->srid = $srid;

        return $geometry;
    }
}
