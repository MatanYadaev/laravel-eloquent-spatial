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
  case GCS = 4326; // https://epsg.org/crs_4326/WGS-84.html
  case UTM = 26717; // https://epsg.org/crs_26717/NAD27-UTM-zone-17N.html
  case SPCS = 6576; // https://epsg.org/crs_6576/NAD83-2011-Tennessee-ftUS.html
}
