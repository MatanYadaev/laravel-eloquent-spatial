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

  public function toWkt(): string
  {
    $wktData = $this->getWktData();

    return "MULTILINESTRING({$wktData})";
  }

  public function getWktData(): string
  {
    return $this->geometries
      ->map(static function (LineString $lineString): string {
        $wktData = $lineString->getWktData();

        return "({$wktData})";
      })
      ->join(', ');
  }
}
