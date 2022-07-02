<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use InvalidArgumentException;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Utils\Helpers;

class GeometryCast implements CastsAttributes
{
    /** @var class-string<Geometry> */
    private string $className;

    /**
     * @param  class-string<Geometry>  $className
     */
    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  string|null  $wkb
     * @param  array<string, mixed>  $attributes
     * @return Geometry|null
     */
    public function get($model, string $key, $wkb, array $attributes): ?Geometry
    {
        if (! $wkb) {
            return null;
        }

        return $this->className::fromJson($wkb);
    }

    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  Geometry|mixed|null  $geometry
     * @param  array<string, mixed>  $attributes
     * @return Expression|string|null
     *
     * @throws InvalidArgumentException
     */
    public function set($model, string $key, $geometry, array $attributes): Expression|string|null
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

    /**
     * Compare current and original attribute values.
     * Returns true if values are equal and false otherwise.
     *
     * @param  mixed  $value
     * @param  mixed  $originalValue
     * @return bool
     */
    public function compare(mixed $value, mixed $originalValue): bool
    {
        if ($value === $originalValue) {
            return true;
        }

        if ($value === null and $originalValue) {
            return false;
        }

        if ($value and is_a($value, Expression::class)) {
            if ($originalValue === null) {
                return false;
            }

            if (Helpers::isJson($originalValue)) {
                $originalValue = $this->className::fromJson($originalValue)->toWkt();

                return $originalValue->getValue() === $value->getValue();
            }
        }

        return false;
    }
}
