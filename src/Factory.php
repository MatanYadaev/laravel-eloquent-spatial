<?php

namespace MatanYadaev\EloquentSpatial;

use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use CrEOF\Geo\WKB\Parser;

class Factory
{
    public static function parse(string $wkb): Geometry
    {
        $srid = substr($wkb, 0, 4);
        $srid = unpack('L', $srid)[1];

        // MySQL adds 4 NULL bytes at the start of the binary
        $wkb = substr($wkb, 4);
        $parsed = (new Parser($wkb))->parse();

        // @TODO check if this line is relevant
        $parsed['srid'] = $srid;

        if ($parsed['type'] === 'POINT') {
            return self::createPoint($parsed['value'][1], $parsed['value'][0], $parsed['srid']);
        }

        if ($parsed['type'] === 'LINESTRING') {
            return self::createLineString($parsed['value'], $parsed['srid']);
        }

        if ($parsed['type'] === 'MULTILINESTRING') {
            return self::createMultiLineString($parsed['value'], $parsed['srid']);
        }

        if ($parsed['type'] === 'POLYGON') {
            return self::createPolygon($parsed['value'], $parsed['srid']);
        }
    }

    protected static function createPoint(float $latitude, float $longitude, ?int $srid = 0): Point
    {
        return new Point($latitude, $longitude, $srid);
    }

    protected static function createLineString(array $pointsArrays, ?int $srid = null)
    {
        $points = [];

        foreach ($pointsArrays as $pointArray) {
            $points[] = self::createPoint($pointArray[1], $pointArray[0]);
        }

        return new LineString($points, $srid);
    }

    public function createLinearRing($dimension, array $points, $srid = null)
    {
        // TODO: Implement createLinearRing() method.
    }

    protected static function createPolygon(array $lineStringsArrays, $srid = null)
    {
        $lineStrings = [];

        foreach ($lineStringsArrays as $lineStringArray) {
            $lineStrings[] = self::createLineString($lineStringArray);
        }

        return new Polygon($lineStrings, $srid);
    }

    public function createMultiPoint($dimension, array $points, $srid = null)
    {
        // TODO: Implement createMultiPoint() method.
    }

    protected static function createMultiLineString(array $lineStringsArrays, $srid = null)
    {
        $lineStrings = [];

        foreach ($lineStringsArrays as $lineStringArray) {
            $lineStrings[] = self::createLineString($lineStringArray);
        }

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
