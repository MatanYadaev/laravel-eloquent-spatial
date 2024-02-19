<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\MySqlConnection;
use PDO;

// @TODO: Is this class really needed?
/** @codeCoverageIgnore */
class AxisOrder
{
  public function __construct()
  {
  }

  public function supported(ConnectionInterface $connection): bool
  {
    if ($this->isMySql8OrAbove($connection)) {
      return true;
    }

    return false;
  }

  private function isMySql8OrAbove(ConnectionInterface $connection): bool
  {
    if (! ($connection instanceof MySqlConnection)) {
      return false;
    }

    /** @var string $version */
    $version = $connection->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION);

    return version_compare($version, '8.0.0', '>=');
  }
}
