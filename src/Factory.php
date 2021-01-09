<?php

namespace MatanYadaev\EloquentSpatial;

use Collection as geoPHPGeometryCollection;
use Geometry as geoPHPGeometry;
use geoPHP;
use Illuminate\Support\Collection;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use Point as geoPHPPoint;

class Factory
{
    public static function parse(string $value): Geometry
    {
        if (is_json($value)) {
            /** @var geoPHPGeometry $geoPHPGeometry */
            $geoPHPGeometry = geoPHP::load($value);
        } else {
            // MySQL adds 4 NULL bytes at the start of the WKB
            $value = substr($value, 4);

            /** @var geoPHPGeometry $geoPHPGeometry */
            $geoPHPGeometry = geoPHP::load($value);
        }

        return self::create($geoPHPGeometry);
    }

    protected static function create(geoPHPGeometry $geometry): Geometry
    {
        if ($geometry instanceof geoPHPGeometryCollection) {
            $components = collect($geometry->components)->map(function (geoPHPGeometry $geometryComponent): Geometry {
                return self::create($geometryComponent);
            })->all();
            $className = get_class($geometry);
            $methodName = "create{$className}";

            return self::$methodName($components);
        }
        if ($geometry instanceof geoPHPPoint) {
            return self::createPoint($geometry->coords[1], $geometry->coords[0]);
        }
    }

    protected static function createPoint(float $latitude, float $longitude): Point
    {
        return new Point($latitude, $longitude);
    }

    /**
     * @param Collection<Point>|Point[] $points
     * @return LineString
     */
    protected static function createLineString(Collection | array $points)
    {
        return new LineString($points);
    }

    /**
     * @param Collection<LineString>|LineString[] $lineStrings
     * @return Polygon
     */
    protected static function createPolygon(Collection | array $lineStrings)
    {
        return new Polygon($lineStrings);
    }

    /**
     * @param Collection<LineString>|LineString[] $lineStrings
     * @return MultiLineString
     */
    protected static function createMultiLineString(Collection | array $lineStrings)
    {
        return new MultiLineString($lineStrings);
    }
}
