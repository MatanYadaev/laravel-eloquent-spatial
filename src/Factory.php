<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Geometry as geoPHPGeometry;
use GeometryCollection as geoPHPGeometryCollection;
use geoPHP;
use InvalidArgumentException;
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
  public static function parse(string $value, bool $isWkb): Geometry
  {
    if ($isWkb) {
      // MySQL adds 4 NULL bytes at the start of the WKB
      $value = substr($value, 4);
    }

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
    if ($geometry instanceof geoPHPPoint) {
      if ($geometry->coords[0] === null || $geometry->coords[1] === null) {
        if (! isset($geoPHPGeometry) || ! $geoPHPGeometry) {
          throw new InvalidArgumentException('Invalid spatial value');
        }
      }

      return new Point($geometry->coords[1], $geometry->coords[0]);
    }

    /** @var geoPHPGeometryCollection $geometry */
    $components = collect($geometry->components)
      ->map(static function (geoPHPGeometry $geometryComponent): Geometry {
        return self::createFromGeometry($geometryComponent);
      });

    if ($geometry::class === geoPHPMultiPoint::class) {
      return new MultiPoint($components);
    }

    if ($geometry::class === geoPHPLineString::class) {
      return new LineString($components);
    }

    if ($geometry::class === geoPHPPolygon::class) {
      return new Polygon($components);
    }

    if ($geometry::class === geoPHPMultiLineString::class) {
      return new MultiLineString($components);
    }

    if ($geometry::class === geoPHPMultiPolygon::class) {
      return new MultiPolygon($components);
    }

    return new GeometryCollection($components);
  }
}
