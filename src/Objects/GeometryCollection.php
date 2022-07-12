<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use ArrayAccess;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * @template TGeometry of Geometry
 *
 * @implements ArrayAccess<int, TGeometry>
 */
class GeometryCollection extends Geometry implements ArrayAccess
{
    /** @var Collection<int, TGeometry> */
    protected Collection $geometries;

    /** @var class-string<TGeometry> */
    protected string $collectionOf = Geometry::class;

    protected int $minimumGeometries = 0;

    /**
     * @param  Collection<int, TGeometry>|array<int, TGeometry>  $geometries
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Collection|array $geometries)
    {
        if (is_array($geometries)) {
            $geometries = collect($geometries);
        }

        $this->geometries = $geometries;

        $this->validateGeometriesType();
        $this->validateGeometriesCount();
    }

    /**
     * @param  bool  $withFunction
     *
     * @return string
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function toWkt(bool $withFunction = true): string
    {
        $wkt = $this->toCollectionWkt(withFunction: true);

        return "GEOMETRYCOLLECTION({$wkt})";
    }

    /**
     * @return array<mixed>
     */
    public function getCoordinates(): array
    {
        return $this->geometries
            ->map(static function (Geometry $geometry): array {
                return $geometry->getCoordinates();
            })
            ->all();
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        // @phpstan-ignore-next-line
        if (static::class === self::class) {
            return [
                'type' => class_basename(static::class),
                'geometries' => $this->geometries->map(static function (Geometry $geometry): array {
                    return $geometry->toArray();
                }),
            ];
        }

        return parent::toArray();
    }

    /**
     * @return Collection<int, TGeometry>
     */
    public function getGeometries(): Collection
    {
        return new Collection($this->geometries->all());
    }

    /**
     * @param  int  $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->geometries[$offset]);
    }

    /**
     * @param  int  $offset
     *
     * @return TGeometry|null
     */
    public function offsetGet($offset): ?Geometry
    {
        return $this->geometries[$offset];
    }

    /**
     * @param  int  $offset
     * @param  TGeometry  $geometry
     */
    public function offsetSet($offset, $geometry): void
    {
        $this->geometries[$offset] = $geometry;
        $this->validateGeometriesType();
    }

    /**
     * @param  int  $offset
     */
    public function offsetUnset($offset): void
    {
        $this->geometries->splice($offset, 1);
        $this->validateGeometriesCount();
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function validateGeometriesCount(): void
    {
        $geometriesCount = $this->geometries->count();
        if ($geometriesCount < $this->minimumGeometries) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must contain at least %s %s',
                    static::class,
                    $this->minimumGeometries,
                    Str::plural('entries', $geometriesCount)
                )
            );
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function validateGeometriesType(): void
    {
        $this->geometries->each(function (mixed $geometry): void {
            /** @var mixed $geometry */
            if (! is_object($geometry) || ! ($geometry instanceof $this->collectionOf)) {
                throw new InvalidArgumentException(
                    sprintf('%s must be a collection of %s', static::class, $this->collectionOf)
                );
            }
        });
    }

    protected function toCollectionWkt(bool $withFunction): string
    {
        return $this->geometries
            ->map(static function (Geometry $geometry) use ($withFunction): string {
                return (string) $geometry->toWkt($withFunction);
            })
            ->join(',');
    }
}
