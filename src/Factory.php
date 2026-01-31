<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Brick\Geo\Geometry as BrickGeometry;
use Brick\Geo\GeometryCollection as BrickGeometryCollection;
use Brick\Geo\IO\EwkbReader;
use Brick\Geo\IO\GeoJSON\Feature;
use Brick\Geo\IO\GeoJSON\FeatureCollection;
use Brick\Geo\IO\GeoJsonReader;
use Brick\Geo\LineString as BrickLineString;
use Brick\Geo\MultiLineString as BrickMultiLineString;
use Brick\Geo\MultiPoint as BrickMultiPoint;
use Brick\Geo\MultiPolygon as BrickMultiPolygon;
use Brick\Geo\Point as BrickPoint;
use Brick\Geo\Polygon as BrickPolygon;
use InvalidArgumentException;
use MatanYadaev\EloquentSpatial\Objects\Geometry;

class Factory
{
    private const HEX_BYTE_ORDER_BIG_ENDIAN = '00';

    private const HEX_BYTE_ORDER_LITTLE_ENDIAN = '01';

    public static function parseWkb(string $wkb): Geometry
    {
        return self::createFromGeometry(
            (new EwkbReader)->read($wkb)
        );
    }

    public static function parseWkt(string $wkt): Geometry
    {
        return self::createFromGeometry(
            BrickGeometry::fromText($wkt)
        );
    }

    public static function parseJson(string $json): Geometry
    {
        $json = self::normalizeGeoJson($json);
        $result = (new GeoJsonReader)->read($json);

        if ($result instanceof FeatureCollection) {
            $geometries = collect($result->getFeatures())
                ->map(static fn (Feature $feature): Geometry => self::createFromGeometry($feature->getGeometry(), false));

            return new EloquentSpatial::$geometryCollection($geometries, 0);
        }

        if ($result instanceof Feature) {
            return self::createFromGeometry($result->getGeometry());
        }

        return self::createFromGeometry($result);
    }

    /**
     * @deprecated Use parseWkb(), parseWkt(), or parseJson() instead
     *
     * @codeCoverageIgnore
     */
    public static function parse(string $value): Geometry
    {
        try {
            if (self::isWkb($value)) {
                return self::parseWkb($value);
            }

            if (self::isHexWkb($value)) {
                return self::parseWkb(hex2bin($value));
            }

            if (self::isJson($value)) {
                return self::parseJson($value);
            }

            return self::parseWkt($value);
        } catch (\Exception $e) {
            throw new InvalidArgumentException('Invalid spatial value');
        }
    }

    /** @codeCoverageIgnore */
    private static function isWkb(string $value): bool
    {
        return ! ctype_print($value) && $value !== '';
    }

    /** @codeCoverageIgnore */
    private static function isHexWkb(string $value): bool
    {
        if (! ctype_xdigit($value)) {
            return false;
        }

        return str_starts_with($value, self::HEX_BYTE_ORDER_BIG_ENDIAN)
            || str_starts_with($value, self::HEX_BYTE_ORDER_LITTLE_ENDIAN);
    }

    /** @codeCoverageIgnore */
    private static function isJson(string $value): bool
    {
        return str_starts_with(ltrim($value), '{');
    }

    /**
     * @TODO: Remove this once toFeatureCollectionJson() is fixed to output "properties": {} instead of "properties": []
     */
    private static function normalizeGeoJson(string $json): string
    {
        return preg_replace('/"properties"\s*:\s*\[\s*\]/', '"properties":{}', $json);
    }

    protected static function createFromGeometry(BrickGeometry $geometry, bool $isRoot = true): Geometry
    {
        $srid = $isRoot ? ($geometry->SRID() ?? 0) : 0;

        if ($geometry instanceof BrickPoint) {
            if ($geometry->isEmpty()) {
                throw new InvalidArgumentException('Invalid spatial value');
            }

            return new EloquentSpatial::$point($geometry->y(), $geometry->x(), $srid);
        }

        if ($geometry instanceof BrickMultiPoint) {
            $components = collect($geometry->geometries())
                ->map(static fn (BrickGeometry $g): Geometry => self::createFromGeometry($g, false));

            return new EloquentSpatial::$multiPoint($components, $srid);
        }

        if ($geometry instanceof BrickLineString) {
            $components = collect($geometry->points())
                ->map(static fn (BrickGeometry $g): Geometry => self::createFromGeometry($g, false));

            return new EloquentSpatial::$lineString($components, $srid);
        }

        if ($geometry instanceof BrickPolygon) {
            $components = collect($geometry->rings())
                ->map(static fn (BrickGeometry $g): Geometry => self::createFromGeometry($g, false));

            return new EloquentSpatial::$polygon($components, $srid);
        }

        if ($geometry instanceof BrickMultiLineString) {
            $components = collect($geometry->geometries())
                ->map(static fn (BrickGeometry $g): Geometry => self::createFromGeometry($g, false));

            return new EloquentSpatial::$multiLineString($components, $srid);
        }

        if ($geometry instanceof BrickMultiPolygon) {
            $components = collect($geometry->geometries())
                ->map(static fn (BrickGeometry $g): Geometry => self::createFromGeometry($g, false));

            return new EloquentSpatial::$multiPolygon($components, $srid);
        }

        /** @var BrickGeometryCollection $geometry */
        $components = collect($geometry->geometries())
            ->map(static fn (BrickGeometry $g): Geometry => self::createFromGeometry($g, false));

        return new EloquentSpatial::$geometryCollection($components, $srid);
    }
}
