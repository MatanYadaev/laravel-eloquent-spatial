<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

/**
 * Utilities for working with Well-Known Binary (WKB) formats.
 *
 * WKB:
 *   [byteOrder:1] [type:4] [data...]
 *
 * EWKB:
 *   [byteOrder:1] [type+flags:4] [srid:4 if flag set] [data...]
 *   Flag 0x20000000 in type indicates SRID is present.
 *
 * MySQL-specific format:
 *   [srid:4 little-endian] [standard WKB...]
 *   MySQL prepends SRID as a separate 4-byte prefix, not using EWKB flags.
 */
final class Wkb
{
    private const BYTE_ORDER_BIG_ENDIAN = 0x00;

    private const BYTE_ORDER_LITTLE_ENDIAN = 0x01;

    private const BYTE_ORDER_LENGTH = 1;

    private const UINT32_LENGTH = 4;

    private const EWKB_FLAG_MASK = 0x1FFFFFFF;

    private const MIN_GEOMETRY_TYPE = 1;

    private const MAX_GEOMETRY_TYPE = 12;

    public static function getMysqlSrid(string $mysqlFormat): int
    {
        return self::unpackUint32(
            substr($mysqlFormat, 0, self::UINT32_LENGTH),
            self::BYTE_ORDER_LITTLE_ENDIAN
        );
    }

    public static function getMysqlWkb(string $mysqlFormat): string
    {
        return substr($mysqlFormat, self::UINT32_LENGTH);
    }

    public static function toMysqlFormat(string $wkb, int $srid): string
    {
        return self::packUint32($srid, self::BYTE_ORDER_LITTLE_ENDIAN).$wkb;
    }

    public static function isMysqlFormat(string $data): bool
    {
        $validAtStart = self::hasValidWkbHeader($data, 0);
        $validAtSridOffset = self::hasValidWkbHeader($data, self::UINT32_LENGTH);

        if ($validAtSridOffset) {
            return true;
        }

        return ! $validAtStart;
    }

    private static function hasValidWkbHeader(string $data, int $offset): bool
    {
        $minLength = $offset + self::BYTE_ORDER_LENGTH + self::UINT32_LENGTH;
        if (strlen($data) < $minLength) {
            return false;
        }

        $byteOrder = ord($data[$offset]);
        if ($byteOrder !== self::BYTE_ORDER_BIG_ENDIAN && $byteOrder !== self::BYTE_ORDER_LITTLE_ENDIAN) {
            return false;
        }

        $typeBytes = substr($data, $offset + self::BYTE_ORDER_LENGTH, self::UINT32_LENGTH);

        return self::isPlausibleWkbType(self::unpackUint32($typeBytes, $byteOrder));
    }

    private static function isPlausibleWkbType(int $type): bool
    {
        $type &= self::EWKB_FLAG_MASK;

        $baseType = $type >= 1000 ? $type % 1000 : $type;
        $dimension = $type >= 1000 ? intdiv($type, 1000) : 0;

        if ($dimension > 3) {
            return false;
        }

        return $baseType >= self::MIN_GEOMETRY_TYPE && $baseType <= self::MAX_GEOMETRY_TYPE;
    }

    private static function unpackUint32(string $bytes, int $byteOrder): int
    {
        $format = self::getPackFormat($byteOrder);

        /** @var array{1: int} $unpacked */
        $unpacked = unpack($format, $bytes);

        return $unpacked[1];
    }

    private static function packUint32(int $value, int $byteOrder): string
    {
        $format = self::getPackFormat($byteOrder);

        return pack($format, $value);
    }

    private static function getPackFormat(int $byteOrder): string
    {
        return $byteOrder === self::BYTE_ORDER_LITTLE_ENDIAN ? 'V' : 'N';
    }
}
