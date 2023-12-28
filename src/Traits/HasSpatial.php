<?php

namespace MatanYadaev\EloquentSpatial\Traits;

use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\SpatialBuilder;

trait HasSpatial
{
  public function newEloquentBuilder($query): SpatialBuilder
  {
    return new SpatialBuilder($query);
  }

  /**
   * @param  array<string, mixed>  $attributes
   * @param  bool  $sync
   * @return $this
   */
  public function setRawAttributes(array $attributes, $sync = false)
  {
    $result = parent::setRawAttributes($attributes, $sync);

    foreach ($attributes as $attribute => $value) {
      $casts = $this->getCasts();
      if (isset($casts[$attribute]) && is_subclass_of($casts[$attribute], Geometry::class)) {
        $spatialAttribute = $this->getAttribute($attribute);

        if ($spatialAttribute instanceof Geometry) {
          $this->attributes[$attribute] = $spatialAttribute;
          $this->original[$attribute] = $spatialAttribute;
        }
      }
    }

    return $result;
  }
}
