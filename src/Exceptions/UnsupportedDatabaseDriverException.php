<?php

namespace MatanYadaev\EloquentSpatial\Exceptions;

use Exception;

class UnsupportedDatabaseDriverException extends Exception
{
    public function __construct(string $dbDriver)
    {
        parent::__construct("Spatial columns aren't supported in '{$dbDriver}' database.");
    }
}
