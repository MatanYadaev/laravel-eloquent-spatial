<?php

namespace MatanYadaev\EloquentSpatial;

use CrEOF\Geo\WKB\Parser;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;

class Factory
{
    public static function parse(string $wkb): Geometry
    {
        // MySQL adds 4 NULL bytes at the start of the binary
        $wkb = substr($wkb, 4);
        $parsed = (new Parser($wkb))->parse();

        if ($parsed['type'] === 'POINT') {
            return self::createPoint($parsed['value'][1], $parsed['value'][0]);
        }

        if ($parsed['type'] === 'LINESTRING') {
            return self::createLineString($parsed['value']);
        }

        if ($parsed['type'] === 'MULTILINESTRING') {
            return self::createMultiLineString($parsed['value']);
        }

        if ($parsed['type'] === 'POLYGON') {
            return self::createPolygon($parsed['value']);
        }
    }

    protected static function createPoint(float $latitude, float $longitude): Point
    {
        return new Point($latitude, $longitude);
    }

    protected static function createLineString(array $pointsArrays)
    {
        $points = [];

        foreach ($pointsArrays as $pointArray) {
            $points[] = self::createPoint($pointArray[1], $pointArray[0]);
        }

        return new LineString($points);
    }

    protected static function createPolygon(array $lineStringsArrays)
    {
        $lineStrings = [];

        foreach ($lineStringsArrays as $lineStringArray) {
            $lineStrings[] = self::createLineString($lineStringArray);
        }

        return new Polygon($lineStrings);
    }

    protected static function createMultiLineString(array $lineStringsArrays)
    {
        $lineStrings = [];

        foreach ($lineStringsArrays as $lineStringArray) {
            $lineStrings[] = self::createLineString($lineStringArray);
        }

        return new MultiLineString($lineStrings);
    }
}
