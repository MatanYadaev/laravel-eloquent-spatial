<?php

namespace MatanYadaev\EloquentSpatial\Constants;

/**
 * Spatial reference identifiers identify the type of coordinate system to use.
 *
 * This includes the most common ones listed on wikipedia
 * Sourced from https://en.wikipedia.org/wiki/Spatial_reference_system
 */
enum SRID: int
{
  case WGS84 = 4326; // https://epsg.org/crs_4326/WGS-84.html
  case WEB_MERCATOR = 3857; //https://epsg.org/crs_3857/WGS-84-Pseudo-Mercator.html
}
