<?php

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * @method Point[] getGeometries()
 */
abstract class PointCollection extends GeometryCollection
{
    /** @var Collection<Point> */
    protected Collection $geometries;

    protected string $collectionOf = Point::class;

    /**
     * @param Collection<Point>|Point[] $geometries
     * @param int|null $srid
     * @throws InvalidArgumentException
     */
    public function __construct(Collection | array $geometries, ?int $srid = 0)
    {
        parent::__construct($geometries, $srid);
    }
}
