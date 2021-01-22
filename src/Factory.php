<?php

namespace MatanYadaev\EloquentSpatial;

use Collection as geoPHPGeometryCollection;
use Geometry as geoPHPGeometry;
use geoPHP;
use Illuminate\Support\Collection;
use LineString as geoPHPLineString;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\GeometryCollection;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MultiLineString as geoPHPMultiLineString;
use MultiPoint as geoPHPMultiPoint;
use MultiPolygon as geoPHPMultiPolygon;
use Point as geoPHPPoint;
use Polygon as geoPHPPolygon;

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

            if ($className === geoPHPMultiPoint::class) {
                return self::createMultiPoint($components);
            }
            if ($className === geoPHPLineString::class) {
                return self::createLineString($components);
            }
            if ($className === geoPHPPolygon::class) {
                return self::createPolygon($components);
            }
            if ($className === geoPHPMultiLineString::class) {
                return self::createMultiLineString($components);
            }
            if ($className === geoPHPMultiPolygon::class) {
                return self::createMultiPolygon($components);
            }
            return self::createGeometryCollection($components);
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
     * @param Point[] $points
     * @return MultiPoint
     */
    protected static function createMultiPoint(array $points): MultiPoint
    {
        return new MultiPoint($points);
    }

    /**
     * @param Point[] $points
     * @return LineString
     */
    protected static function createLineString(array $points): LineString
    {
        return new LineString($points);
    }

    /**
     * @param LineString[] $lineStrings
     * @return Polygon
     */
    protected static function createPolygon(array $lineStrings): Polygon
    {
        return new Polygon($lineStrings);
    }

    /**
     * @param LineString[] $lineStrings
     * @return MultiLineString
     */
    protected static function createMultiLineString(array $lineStrings):MultiLineString
    {
        return new MultiLineString($lineStrings);
    }

    /**
     * @param Polygon[] $polygons
     * @return MultiPolygon
     */
    protected static function createMultiPolygon(array $polygons): MultiPolygon
    {
        return new MultiPolygon($polygons);
    }

    /**
     * @param Geometry[] $geometries
     * @return GeometryCollection
     */
    protected static function createGeometryCollection(array $geometries): GeometryCollection
    {
        return new GeometryCollection($geometries);
    }
}
