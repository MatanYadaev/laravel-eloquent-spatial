<?php

namespace MatanYadaev\EloquentSpatial\Traits;

use MatanYadaev\EloquentSpatial\SpatialBuilder;

trait HasSpatial
{
  public function newEloquentBuilder($query): SpatialBuilder
  {
    return new SpatialBuilder($query);
  }
}
