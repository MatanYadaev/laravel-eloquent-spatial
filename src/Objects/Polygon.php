<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

class Polygon extends MultiLineString
{
  public function toWkt(): string
  {
    $wktData = $this->getWktData();

    return "POLYGON({$wktData})";
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
