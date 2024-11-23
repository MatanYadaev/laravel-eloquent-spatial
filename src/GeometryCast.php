<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
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
     * @param  string|ExpressionContract|null  $value
     * @param  array<string, mixed>  $attributes
     */
    public function get($model, string $key, $value, array $attributes): ?Geometry
    {
        if (! $value) {
            return null;
        }

        if ($value instanceof ExpressionContract) {
            ['wkt' => $wkt, 'srid' => $srid] = $this->extractValuesFromExpression($value, $model->getConnection());

            return $this->className::fromWkt($wkt, $srid);
        }

        return $this->className::fromWkb($value);
    }

    /**
     * @param  Model  $model
     * @param  Geometry|mixed|null  $value
     * @param  array<string, mixed>  $attributes
     *
     * @throws InvalidArgumentException
     */
    public function set($model, string $key, $value, array $attributes): ?ExpressionContract
    {
        if (! $value) {
            return null;
        }

        if (is_array($value)) {
            $value = Geometry::fromArray($value);
        }

        if ($value instanceof ExpressionContract) {
            return $value;
        }

        if (! $this->isCorrectGeometryType($value)) {
            $geometryType = is_object($value) ? $value::class : gettype($value);
            throw new InvalidArgumentException(
                sprintf('Expected %s, %s given.', $this->className, $geometryType)
            );
        }

        /** @var Geometry $value */

        return $value->toSqlExpression($model->getConnection());
    }

    private function isCorrectGeometryType(mixed $value): bool
    {
        if ($this->className === Geometry::class && $value instanceof Geometry) {
            return true;
        }

        return $value instanceof $this->className && get_class($value) === $this->className;
    }

    /**
     * @return array{wkt: string, srid: int}
     */
    private function extractValuesFromExpression(ExpressionContract $expression, Connection $connection): array
    {
        $grammar = $connection->getQueryGrammar();
        $expressionValue = $expression->getValue($grammar);

        preg_match("/ST_GeomFromText\(\s*'([^']+)'\s*(?:,\s*(\d+))?\s*(?:,\s*'([^']+)')?\s*\)/", (string) $expressionValue, $matches);

        return [
            'wkt' => $matches[1] ?? '',
            'srid' => (int) ($matches[2] ?? 0),
        ];
    }
}
