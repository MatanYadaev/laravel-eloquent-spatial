<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use MatanYadaev\EloquentSpatial\Objects\Geometry;

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
     * @param  string|Expression|null  $wkbOrWKt
     * @param  array<string, mixed>  $attributes
     * @return Geometry|null
     */
    public function get($model, string $key, $wkbOrWKt, array $attributes): ?Geometry
    {
        if (! $wkbOrWKt) {
            return null;
        }

        if ($wkbOrWKt instanceof Expression) {
            $wkt = $this->extractWktFromExpression($wkbOrWKt);

            return $this->className::fromWkt($wkt);
        }

        return $this->className::fromWkb($wkbOrWKt);
    }

    /**
     * @param  Model  $model
     * @param  string  $key
     * @param  Geometry|mixed|null  $geometry
     * @param  array<string, mixed>  $attributes
     * @return Expression|null
     *
     * @throws InvalidArgumentException
     */
    public function set($model, string $key, $geometry, array $attributes): Expression|null
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

        $wkt = $geometry->toWkt(withFunction: true);

        return DB::raw("ST_GeomFromText('{$wkt}')");
    }

    private function extractWktFromExpression(Expression $expression): string
    {
        preg_match('/ST_GeomFromText\(\'(.+)\'\)/', (string) $expression, $match);

        return $match[1];
    }
}
