<?php

namespace MatanYadaev\EloquentSpatial;

use GeoIO\WKB\Parser\Parser as GeoIOParser;

class Parser
{
    public static function parse(string $wkb): Geometry
    {
        $srid = substr($wkb, 0, 4);
        $srid = unpack('L', $srid)[1];

        $wkb = substr($wkb, 4);

        $geometry = (new GeoIOParser(
            new Factory(),
        ))->parse($wkb);

        $geometry->srid = $srid;

        return $geometry;
    }
}
