<?php

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Query\Expression;
use MatanYadaev\EloquentSpatial\Parser;

abstract class Geometry implements Castable
{
    public int $srid;

    public function __construct(?int $srid = 0)
    {
        $this->srid = (int) $srid;
    }

    abstract public function toWkt(): Expression;

    public static function castUsing(array $arguments): CastsAttributes
    {
        $className = static::class;

        return new class($className) implements CastsAttributes {
            private string $className;

            public function __construct(string $className)
            {
                $this->className = $className;
            }

            public function get($model, string $key, $value, array $attributes)
            {
                if (! $value) {
                    return null;
                }

                $geometry = Parser::parse($value);

                throw_invalid_type_exception_if_not_instanceof($geometry, $this->className);

                return $geometry;
            }

            public function set($model, string $key, $value, array $attributes): Expression | string | null
            {
                if (! $value) {
                    return null;
                }

                throw_invalid_type_exception_if_not_instanceof($value, $this->className);

                return $value->toWkt();
            }
        };
    }
}
