<?php

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Query\Expression;
use JsonSerializable;
use MatanYadaev\EloquentSpatial\Exceptions\InvalidTypeException;
use MatanYadaev\EloquentSpatial\Factory;

abstract class Geometry implements Castable, Arrayable, Jsonable, JsonSerializable
{
    abstract public function toWkt(): Expression;

    /**
     * @param string $wkt
     * @return static
     * @throws InvalidTypeException
     */
    public static function fromWkt(string $wkt): static
    {
        $geometry = Factory::parse($wkt);

        if (! ($geometry instanceof static)) {
            throw new InvalidTypeException(static::class, $geometry);
        }

        return $geometry;
    }

    public function toJson($options = 0): string
    {
        return json_encode($this, $options);
    }

    /**
     * @param string $geoJson
     * @return static
     * @throws InvalidTypeException
     */
    public static function fromJson(string $geoJson): static
    {
        $geometry = Factory::parse($geoJson);

        if (! ($geometry instanceof static)) {
            throw new InvalidTypeException(static::class, $geometry);
        }

        return $geometry;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'type' => class_basename(static::class),
            'coordinates' => $this->getCoordinates(),
        ];
    }

    abstract public function getCoordinates(): array;

    public static function castUsing(array $arguments): CastsAttributes
    {
        $className = static::class;

        return new class($className) implements CastsAttributes {
            private string $className;

            public function __construct(string $className)
            {
                $this->className = $className;
            }

            /**
             * @param $model
             * @param string $key
             * @param string|null $wkt
             * @param array $attributes
             * @return null
             */
            public function get($model, string $key, $wkt, array $attributes)
            {
                if (! $wkt) {
                    return;
                }

                return $this->className::fromWkt($wkt);
            }

            /**
             * @param $model
             * @param string $key
             * @param static $geometry
             * @param array $attributes
             * @return Expression|string|null
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
        };
    }
}
