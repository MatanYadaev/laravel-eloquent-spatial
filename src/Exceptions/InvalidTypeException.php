<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Exceptions;

use Exception;

class InvalidTypeException extends Exception
{
    public function __construct(string $expectedType, mixed $actual)
    {
        $actualType = is_object($actual) ? get_class($actual) : gettype($actual);

        parent::__construct("Expected {$expectedType}, {$actualType} given.");
    }
}
