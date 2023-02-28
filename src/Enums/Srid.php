<?php

namespace MatanYadaev\EloquentSpatial\Enums;

enum Srid: int
{
  case WGS84 = 4326; // https://epsg.org/crs_4326/WGS-84.html
  case WEB_MERCATOR = 3857; //https://epsg.org/crs_3857/WGS-84-Pseudo-Mercator.html
}
