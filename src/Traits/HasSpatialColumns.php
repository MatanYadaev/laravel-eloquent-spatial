<?php

namespace MatanYadaev\EloquentSpatial\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use MatanYadaev\EloquentSpatial\Objects\GeometryCollection;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\SpatialBuilder;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasSpatialColumns
{
    private array $spatialTypes = [
        Point::class,
        MultiPoint::class,
        LineString::class,
        MultiLineString::class,
        Polygon::class,
        MultiPolygon::class,
        GeometryCollection::class,
    ];

    /**
     * @inheritDoc
     */
    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }

    /**
     * @inheritDoc
     */
    public function setRawAttributes(array $attributes, $sync = false): self
    {
        foreach ($this->getSpatialColumns() as $column => $type) {
            if (isset($attributes[$column])) {
                $value = $attributes[$column];

                if (! $value or ! Str::isJson($value)) {
                    $attributes[$column] = $value ? $type::fromWkb($value)->toJson() : null;
                }
            }
        }

        return parent::setRawAttributes($attributes, $sync);
    }

    /**
     * @inheritDoc
     */
    public function originalIsEquivalent($key): bool
    {
        if (! array_key_exists($key, $this->original)) {
            return false;
        }

        if ($this->isClassComparable($key)) {
            $attribute = Arr::get($this->attributes, $key);
            $original = Arr::get($this->original, $key);

            return $this->resolveCasterClass($key)->compare($attribute, $original);
        }

        return parent::originalIsEquivalent($key);
    }

    /**
     * @return array
     */
    private function getSpatialColumns(): array
    {
        $columns = [];

        foreach ($this->casts as $key => $cast) {
            if (in_array($cast, $this->spatialTypes)) {
                $columns[$key] = $cast;
            }
        }

        return $columns;
    }

    /**
     * Determine if an attribute can be checked for being dirty using a custom class.
     *
     * @param  string  $key
     * @return bool
     *
     * @throws \Illuminate\Database\Eloquent\InvalidCastException
     */
    private function isClassComparable(string $key): bool
    {
        if ($this->isClassCastable($key)) {
            $castClass = $this->parseCasterClass($this->getCasts()[$key]);

            if (method_exists($castClass, 'compare')) {
                return true;
            }

            if (method_exists($castClass, 'castUsing')) {
                $castClass = $castClass::castUsing([]);

                if (method_exists($castClass, 'compare')) {
                    return true;
                }
            }
        }

        return false;
    }
}
