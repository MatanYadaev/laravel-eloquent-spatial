<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * @method array<Point> getGeometries()
 */
abstract class PointCollection extends GeometryCollection
{
    /** @var Collection<Point> */
    protected Collection $geometries;

    protected string $collectionOf = Point::class;

    /**
     * @param Collection<Point>|array<Point> $geometries
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Collection | array $geometries)
    {
        parent::__construct($geometries);
    }

    /**
     * @return array<Point>
     */
    public function getGeometries(): array
    {
        return parent::getGeometries();
    }
}
