<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use ArrayAccess;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * @template T of Geometry
 */
class GeometryCollection extends Geometry implements ArrayAccess
{
    /** @var Collection<int, T> */
    protected Collection $geometries;

    /** @var class-string<T> */
    protected string $collectionOf = Geometry::class;

    protected int $minimumGeometries = 0;

    /**
     * @param  Collection<int, T>|array<int, T>  $geometries
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

    public function toWkt(): Expression
    {
        return new Expression("GEOMETRYCOLLECTION({$this->toCollectionWkt()})");
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
     * @return Collection<int, T>
     */
    public function getGeometries(): Collection
    {
        return new Collection($this->geometries->all());
    }

    /**
     * @param  int  $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->geometries[$offset]);
    }

    /**
     * @param  int  $offset
     * @return T|null
     */
    public function offsetGet($offset): ?Geometry
    {
        return $this->geometries[$offset];
    }

    /**
     * @param  int  $offset
     * @param  T  $geometry
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
            if (! is_object($geometry) || ! ($geometry instanceof $this->collectionOf)) {
                throw new InvalidArgumentException(
                    sprintf('%s must be a collection of %s', static::class, $this->collectionOf)
                );
            }
        });
    }

    protected function toCollectionWkt(): Expression
    {
        $wkt = $this->geometries
            ->map(static function (Geometry $geometry): string {
                return (string) $geometry->toWkt();
            })
            ->join(',');

        return DB::raw($wkt);
    }
}
