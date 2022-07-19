<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * @property Collection<int, LineString> $geometries
 *
 * @method Collection<int, LineString> getGeometries()
 * @method LineString offsetGet(int $offset)
 * @method void offsetSet(int $offset, LineString $value)
 */
class MultiLineString extends GeometryCollection
{
  protected string $collectionOf = LineString::class;

  protected int $minimumGeometries = 1;

  /**
   * @param  Collection<int, LineString>|array<int, LineString>  $geometries
   * @param  int  $srid
   *
   * @throws InvalidArgumentException
   */
  public function __construct(Collection|array $geometries, int $srid = 0)
  {
    // @phpstan-ignore-next-line
    parent::__construct($geometries, $srid);
  }

  public function toWkt(bool $withFunction = true): string
  {
    $wkt = $this->toCollectionWkt(withFunction: false);

    return "MULTILINESTRING({$wkt})";
  }
}
