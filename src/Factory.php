<?php

namespace MatanYadaev\EloquentSpatial;

use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;

class Factory implements \GeoIO\Factory
{
    public function createPoint($dimension, array $coordinates, $srid = null): Point
    {
        return new Point($coordinates['y'], $coordinates['x'], $srid);
    }

    public function createLineString($dimension, array $points, $srid = null)
    {
        return new LineString($points, $srid);
    }

    public function createLinearRing($dimension, array $points, $srid = null)
    {
        // TODO: Implement createLinearRing() method.
    }

    public function createPolygon($dimension, array $lineStrings, $srid = null)
    {
        dump($lineStrings);
        return new Polygon($lineStrings, $srid);
    }

    public function createMultiPoint($dimension, array $points, $srid = null)
    {
        // TODO: Implement createMultiPoint() method.
    }

    public function createMultiLineString($dimension, array $lineStrings, $srid = null)
    {
        return new MultiLineString($lineStrings, $srid);
    }

    public function createMultiPolygon($dimension, array $polygons, $srid = null)
    {
        // TODO: Implement createMultiPolygon() method.
    }

    public function createGeometryCollection($dimension, array $geometries, $srid = null)
    {
        // TODO: Implement createGeometryCollection() method.
    }
}
