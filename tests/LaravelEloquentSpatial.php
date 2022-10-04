<?php

namespace MatanYadaev\EloquentSpatial\Tests;

use MatanYadaev\EloquentSpatial\Objects\GeometryCollection;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;

class LaravelEloquentSpatial
{
    /** @var class-string<Point> */
    public static string $pointClass = Point::class;

    /** @var class-string<LineString> */
    public static string $lineStringClass = LineString::class;

    /** @var class-string<MultiPoint> */
    public static string $multiPointClass = MultiPoint::class;

    /** @var class-string<Polygon> */
    public static string $polygonClass = Polygon::class;

    /** @var class-string<MultiLineString> */
    public static string $multiLineStringClass = MultiLineString::class;

    /** @var class-string<MultiPolygon> */
    public static string $multiPolygonClass = MultiPolygon::class;

    /** @var class-string<GeometryCollection> */
    public static string $geometryCollectionClass = GeometryCollection::class;
}
