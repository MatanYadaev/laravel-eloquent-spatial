<?php

namespace MatanYadaev\EloquentSpatial\Exceptions;

use Exception;

class InvalidTypeException extends Exception
{
    public function __construct(string $expectedType, mixed $actual)
    {
        $expectedType = class_basename($expectedType);
        $actualType = is_object($actual) ? get_class($actual) : gettype($actual);

        parent::__construct("Expected {$expectedType}, {$actualType} given.");
    }
}
