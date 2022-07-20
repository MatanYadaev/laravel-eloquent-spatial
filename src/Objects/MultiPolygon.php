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
   * @param  int  $srid
   *
   * @throws InvalidArgumentException
   */
  public function __construct(Collection|array $geometries, int $srid = 0)
  {
    // @phpstan-ignore-next-line
    parent::__construct($geometries, $srid);
  }

  public function toWkt(): string
  {
    $wktData = $this->getWktData();

    return "MULTIPOLYGON({$wktData})";
  }

  public function getWktData(): string
  {
    return $this->geometries
      ->map(static function (Polygon $polygon): string {
        $wktData = $polygon->getWktData();

        return "({$wktData})";
      })
      ->join(', ');
  }
}
