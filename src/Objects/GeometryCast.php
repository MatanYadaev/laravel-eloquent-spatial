<?php

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use MatanYadaev\EloquentSpatial\Exceptions\InvalidTypeException;

class GeometryCast implements CastsAttributes
{
    private string $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * @param Model $model
     * @param string $key
     * @param string|null $wkt
     * @param array<string, mixed> $attributes
     *
     * @return Geometry|null
     */
    public function get($model, string $key, $wkt, array $attributes): ?Geometry
    {
        if (! $wkt) {
            return null;
        }

        return $this->className::fromWkt($wkt, false);
    }

    /**
     * @param Model $model
     * @param string $key
     * @param Geometry|null $geometry
     * @param array<string, mixed> $attributes
     *
     * @return Expression|string|null
     * @throws InvalidTypeException
     */
    public function set($model, string $key, $geometry, array $attributes): Expression | string | null
    {
        if (! $geometry) {
            return null;
        }

        if (! ($geometry instanceof $this->className)) {
            throw new InvalidTypeException($this->className, $geometry);
        }

        return $geometry->toWkt();
    }
}
