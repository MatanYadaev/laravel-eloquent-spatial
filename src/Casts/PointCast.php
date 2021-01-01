<?php

namespace MatanYadaev\EloquentSpatial\Casts;

use Exception;
use GeoIO\WKB\Parser\Parser;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Factory;
use MatanYadaev\EloquentSpatial\Point;

class PointCast implements CastsAttributes
{
    /**
     * @param Model $model
     * @param string $key
     * @param string|null $PointWkb
     * @param array $attributes
     * @return Point|void
     */
    public function get($model, string $key, $PointWkb, array $attributes)
    {
        if (! $PointWkb) {
            return null;
        }

        $dbDriver = $model->getConnection()->getDriverName();

        if (! in_array($dbDriver, ['mysql'])) {
            throw new Exception('Bad driver');
        }

        $PointWkb = substr($PointWkb, 4);

        $parser = (new Parser(new Factory));

        /** @var Point $point */
        $point = $parser->parse($PointWkb);

        return $point;
    }

    /**
     * @param Model $model
     * @param string $key
     * @param Point|null $point
     * @param array $attributes
     * @return string|void
     */
    public function set($model, string $key, $point, array $attributes)
    {
        if (! $point) {
            return null;
        }

        $dbDriver = $model->getConnection()->getDriverName();

        if (! in_array($dbDriver, ['mysql'])) {
            // @TODO use proper exception
            throw new Exception('Bad driver');
        }

        if ($dbDriver === 'mysql') {
            if ($point->srid) {
                return DB::raw("ST_SRID( POINT( {$point->longitude},{$point->latitude} ), {$point->srid} )");
            }
            return DB::raw("POINT( {$point->longitude},{$point->latitude} )");
        }
    }
}
