<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Support\Collection;
use InvalidArgumentException;

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
   * @param  int  $srid
   *
   * @throws InvalidArgumentException
   */
  public function __construct(Collection|array $geometries, int $srid = 0)
  {
    // @phpstan-ignore-next-line
    parent::__construct($geometries, $srid);
  }
}
