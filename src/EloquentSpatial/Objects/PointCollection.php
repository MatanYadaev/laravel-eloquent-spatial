<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

/**
 * @method array<Point> getGeometries()
 * @method Point offsetGet(mixed $offset)
 * @extends GeometryCollection<Point>
 */
abstract class PointCollection extends GeometryCollection
{
    protected string $collectionOf = Point::class;
}
