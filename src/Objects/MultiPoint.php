<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

class MultiPoint extends PointCollection
{
  protected int $minimumGeometries = 1;

  public function toWkt(bool $withFunction = true): string
  {
    $wkt = $this->toCollectionWkt(withFunction: false);

    return "MULTIPOINT({$wkt})";
  }
}
