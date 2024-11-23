<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\GeometryCollection;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;

class EloquentSpatial
{
    /** @var class-string<GeometryCollection> */
    public static string $geometryCollection = GeometryCollection::class;

    /** @var class-string<LineString> */
    public static string $lineString = LineString::class;

    /** @var class-string<MultiLineString> */
    public static string $multiLineString = MultiLineString::class;

    /** @var class-string<MultiPoint> */
    public static string $multiPoint = MultiPoint::class;

    /** @var class-string<MultiPolygon> */
    public static string $multiPolygon = MultiPolygon::class;

    /** @var class-string<Point> */
    public static string $point = Point::class;

    /** @var class-string<Polygon> */
    public static string $polygon = Polygon::class;

    public static int $defaultSrid = 0;

    /**
     * @param  class-string<GeometryCollection>  $class
     */
    public static function useGeometryCollection(string $class): string
    {
        static::$geometryCollection = $class;

        return static::$geometryCollection;
    }

    /**
     * @param  class-string<LineString>  $class
     */
    public static function useLineString(string $class): string
    {
        static::$lineString = $class;

        return static::$lineString;
    }

    /**
     * @param  class-string<MultiLineString>  $class
     */
    public static function useMultiLineString(string $class): string
    {
        static::$multiLineString = $class;

        return static::$multiLineString;
    }

    /**
     * @param  class-string<MultiPoint>  $class
     */
    public static function useMultiPoint(string $class): string
    {
        static::$multiPoint = $class;

        return static::$multiPoint;
    }

    /**
     * @param  class-string<MultiPolygon>  $class
     */
    public static function useMultiPolygon(string $class): string
    {
        static::$multiPolygon = $class;

        return static::$multiPolygon;
    }

    /**
     * @param  class-string<Point>  $class
     */
    public static function usePoint(string $class): string
    {
        static::$point = $class;

        return static::$point;
    }

    /**
     * @param  class-string<Polygon>  $class
     */
    public static function usePolygon(string $class): string
    {
        static::$polygon = $class;

        return static::$polygon;
    }

    public static function setDefaultSrid(Srid|int $srid): void
    {
        static::$defaultSrid = $srid instanceof Srid ? $srid->value : $srid;
    }
}
