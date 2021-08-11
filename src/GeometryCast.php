<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use InvalidArgumentException;
use MatanYadaev\EloquentSpatial\Objects\Geometry;

class GeometryCast implements CastsAttributes
{
    /** @var class-string<Geometry> */
    private string $className;

    /**
     * @param class-string<Geometry> $className
     */
    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * @param Model $model
     * @param string $key
     * @param string|null $wkb
     * @param array<string, mixed> $attributes
     *
     * @return Geometry|null
     */
    public function get($model, string $key, $wkb, array $attributes): ?Geometry
    {
        if (! $wkb) {
            return null;
        }

        return $this->className::fromWkb($wkb);
    }

    /**
     * @param Model $model
     * @param string $key
     * @param Geometry|mixed|null $geometry
     * @param array<string, mixed> $attributes
     *
     * @return Expression|string|null
     *
     * @throws InvalidArgumentException
     */
    public function set($model, string $key, $geometry, array $attributes): Expression | string | null
    {
        if (! $geometry) {
            return null;
        }

        if (! ($geometry instanceof $this->className)) {
            $geometryType = is_object($geometry) ? $geometry::class : gettype($geometry);
            throw new InvalidArgumentException(
                sprintf('Expected %s, %s given.', static::class, $geometryType)
            );
        }

        return $geometry->toWkt();
    }
}
