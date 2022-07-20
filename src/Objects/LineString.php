<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

class LineString extends PointCollection
{
  protected int $minimumGeometries = 2;

  public function toWkt(): string
  {
    $wktData = $this->getWktData();

    return "LINESTRING({$wktData})";
  }

  public function getWktData(): string
  {
    return $this->geometries
      ->map(static function (Point $point): string {
        return $point->getWktData();
      })
      ->join(', ');
  }
}
