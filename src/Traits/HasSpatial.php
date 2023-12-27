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
   */
  public function setRawAttributes(array $attributes, $sync = false)
  {
    $result = parent::setRawAttributes($attributes, $sync);

    foreach ($attributes as $attribute => $value) {
      if ($value && is_string($value) && ! preg_match('//u', $value)) { // the string is binary
        // access the attribute to force conversion via attribute cast
        $spatialAttribute = $this->$attribute;

        // override attribute and original attribute to get rid of binary strings
        // (Those would lead to errors while JSON encoding a serialized version of the model.)
        if ($spatialAttribute instanceof Geometry) {
          $this->attributes[$attribute] = $spatialAttribute;
          $this->original[$attribute] = $spatialAttribute;
        }
      }
    }

    return $result;
  }
}
