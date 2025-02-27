<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use MatanYadaev\EloquentSpatial\Enums\Srid;

/**
 * @property Collection<int, Point> $geometries
 *
 * @method Collection<int, Point> getGeometries()
 * @method Point offsetGet(int $offset)
 * @method void offsetSet(int $offset, Point $value)
 */
abstract class PointCollection extends GeometryCollection
{
    protected string $collectionOf = Point::class;

    /**
     * @param  Collection<int, Point>|array<int, Point>  $geometries
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Collection|array $geometries, int|Srid|null $srid = null)
    {
        // @phpstan-ignore-next-line
        parent::__construct($geometries, $srid);
    }
}
