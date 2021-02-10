<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class GeometryCollection extends Geometry
{
    /** @var Collection<Geometry> */
    protected Collection $geometries;

    protected string $collectionOf = Geometry::class;

    protected int $minimumGeometries = 0;

    /**
     * @param Collection<Geometry>|array<Geometry> $geometries
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Collection | array $geometries)
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
     * @return array<Geometry>
     */
    public function getGeometries(): array
    {
        return $this->geometries->all();
    }

    public function toFeatureCollectionJson(): string
    {
        /** @var Collection<Geometry> $geometries */
        $geometries = static::class === self::class
            ? $this->geometries
            : collect([$this]);

        $features = $geometries->map(static function (Geometry $geometry): array {
            return $geometry->toFeature();
        });

        return json_encode([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
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
        $wkb = $this->geometries
            ->map(static function (Geometry $geometry): string {
                return (string) $geometry->toWkt();
            })
            ->join(',');

        return DB::raw($wkb);
    }
}
