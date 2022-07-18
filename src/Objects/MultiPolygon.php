<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * @property Collection<int, Polygon> $geometries
 *
 * @method Collection<int, Polygon> getGeometries()
 * @method Polygon offsetGet(int $offset)
 * @method void offsetSet(int $offset, Polygon $value)
 */
class MultiPolygon extends GeometryCollection
{
  protected string $collectionOf = Polygon::class;

  protected int $minimumGeometries = 1;

  /**
   * @param  Collection<int, Polygon>|array<int, Polygon>  $geometries
   *
   * @throws InvalidArgumentException
   */
  public function __construct(Collection|array $geometries)
  {
    // @phpstan-ignore-next-line
    parent::__construct($geometries);
  }

  public function toWkt(bool $withFunction = true): string
  {
    $wkt = $this->toCollectionWkt(withFunction: false);

    return "MULTIPOLYGON({$wkt})";
  }
}
