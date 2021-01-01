<?php

namespace MatanYadaev\EloquentSpatial;

use Exception;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Exceptions\InvalidTypeException;
use MatanYadaev\EloquentSpatial\Exceptions\UnsupportedDatabaseDriverException;

class Point extends Geometry implements Castable
{
    public float $latitude;

    public float $longitude;

    public function __construct(float $latitude, float $longitude, ?int $srid = 0)
    {
        parent::__construct($srid);
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public static function castUsing(array $arguments): CastsAttributes
    {
        return new class implements CastsAttributes {
            public function get($model, string $key, $value, array $attributes): ?Point
            {
                if (! $value) {
                    return null;
                }

                $dbDriver = $model->getConnection()->getDriverName();

                if (! in_array($dbDriver, ['mysql'])) {
                    throw new UnsupportedDatabaseDriverException($dbDriver);
                }

                $point = Parser::parse($value);

                if (! ($point instanceof Point)) {
                    throw new InvalidTypeException(Point::class, $value);
                }

                return $point;
            }

            public function set($model, string $key, $value, array $attributes): ?Expression
            {
                if (! $value) {
                    return null;
                }

                if (! ($value instanceof Point)) {
                    throw new InvalidTypeException(Point::class, $value);
                }

                $dbDriver = $model->getConnection()->getDriverName();

                if (! in_array($dbDriver, ['mysql'])) {
                    throw new UnsupportedDatabaseDriverException($dbDriver);
                }

                if ($dbDriver === 'mysql') {
                    $expression = DB::raw("POINT({$value->longitude}, {$value->latitude})");

                    if ($value->srid) {
                        $expression = DB::raw("ST_SRID({$expression}, {$value->srid})");
                    }

                    return $expression;
                }
            }
        };
    }
}
