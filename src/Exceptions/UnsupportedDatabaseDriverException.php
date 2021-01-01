<?php


namespace MatanYadaev\EloquentSpatial\Exceptions;

use Exception;

class UnsupportedDatabaseDriverException extends Exception
{
    public function __construct(string $dbDriver)
    {
        parent::__construct("laravel-eloquent-spatial doesn't support '{$dbDriver}' database driver");
    }
}
