<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\MySqlConnection;
use PDO;

/** @codeCoverageIgnore */
class AxisOrder
{
    public static function supported(ConnectionInterface $connection): bool
    {
        if (self::isMariaDb($connection)) {
            return false;
        }

        if (self::isMySql8OrAbove($connection)) {
            return true;
        }

        return false;
    }

    private static function isMariaDb(ConnectionInterface $connection): bool
    {
        if (! ($connection instanceof MySqlConnection)) {
            return false;
        }

        return $connection->isMaria();
    }

    private static function isMySql8OrAbove(ConnectionInterface $connection): bool
    {
        if (! ($connection instanceof MySqlConnection)) {
            return false;
        }

        /** @var string $version */
        $version = $connection->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION);

        return version_compare($version, '8.0.0', '>=');
    }
}
