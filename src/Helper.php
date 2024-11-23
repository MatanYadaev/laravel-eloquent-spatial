<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use MatanYadaev\EloquentSpatial\Enums\Srid;

class Helper
{
    public static function getSrid(Srid|int|null $srid = null): int
    {
        if ($srid instanceof Srid) {
            return $srid->value;
        }

        if (is_int($srid)) {
            return $srid;
        }

        return EloquentSpatial::$defaultSrid;
    }
}
