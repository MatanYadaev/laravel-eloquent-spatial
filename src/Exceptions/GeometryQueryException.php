<?php

namespace MatanYadaev\EloquentSpatial\Exceptions;

use Exception;
use Throwable;

class GeometryQueryException extends Exception
{
  public static function noData(int $code = 0, ?Throwable $previous = null): self
  {
    return new self('No data was returned by the query', $code, $previous);
  }
}
