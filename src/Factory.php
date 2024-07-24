<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Geometry as geoPHPGeometry;
use GeometryCollection as geoPHPGeometryCollection;
use geoPHP;
use InvalidArgumentException;
use LineString as geoPHPLineString;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MultiLineString as geoPHPMultiLineString;
use MultiPoint as geoPHPMultiPoint;
use MultiPolygon as geoPHPMultiPolygon;
use Point as geoPHPPoint;
use Polygon as geoPHPPolygon;

class Factory
{
    public static function parse(string $value): Geometry
    {
        try {
            /** @var geoPHPGeometry|false $geoPHPGeometry */
            $geoPHPGeometry = geoPHP::load($value);
        } finally {
            if (! isset($geoPHPGeometry) || ! $geoPHPGeometry) {
                throw new InvalidArgumentException('Invalid spatial value');
            }
        }

        return self::createFromGeometry($geoPHPGeometry);
    }

    protected static function createFromGeometry(geoPHPGeometry $geometry): Geometry
    {
        $srid = is_int($geometry->getSRID()) ? $geometry->getSRID() : 0;

        if ($geometry instanceof geoPHPPoint) {
            if ($geometry->coords[0] === null || $geometry->coords[1] === null) {
                throw new InvalidArgumentException('Invalid spatial value');
            }

            return new EloquentSpatial::$point($geometry->coords[1], $geometry->coords[0], $srid);
        }

        /** @var geoPHPGeometryCollection $geometry */
        $components = collect($geometry->components)
            ->map(static function (geoPHPGeometry $geometryComponent): Geometry {
                return self::createFromGeometry($geometryComponent);
            });

        if ($geometry::class === geoPHPMultiPoint::class) {
            return new EloquentSpatial::$multiPoint($components, $srid);
        }

        if ($geometry::class === geoPHPLineString::class) {
            return new EloquentSpatial::$lineString($components, $srid);
        }

        if ($geometry::class === geoPHPPolygon::class) {
            return new EloquentSpatial::$polygon($components, $srid);
        }

        if ($geometry::class === geoPHPMultiLineString::class) {
            return new EloquentSpatial::$multiLineString($components, $srid);
        }

        if ($geometry::class === geoPHPMultiPolygon::class) {
            return new EloquentSpatial::$multiPolygon($components, $srid);
        }

        return new EloquentSpatial::$geometryCollection($components, $srid);
    }
}
