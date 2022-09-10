<?php
namespace MatanYadaev\EloquentSpatial\Traits;

use PDO;

trait AxisOrder
{
    private function useWithoutAxisOrder($model): bool
    {
        $isMariaDb = $model->getConnection()->isMaria();
        $isMysql57 = $this->isMysql57($model);

        return $isMariaDb || $isMysql57;
    }

    private function isMysql57($model): bool
    {
        $driver = $model->getConnection()->getPdo()->getAttribute(PDO::ATTR_DRIVER_NAME);
        $version = $model->getConnection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION);

        return ($driver == 'mysql' && floatval($version) < 5.8);
    }
}
