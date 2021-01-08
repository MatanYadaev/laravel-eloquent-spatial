<?php

use MatanYadaev\EloquentSpatial\Exceptions\InvalidTypeException;

if (! function_exists('throw_if_not_instanceof')) {
    /**
     * @param mixed $value
     * @param string $class
     * @throws InvalidTypeException
     */
    function throw_invalid_type_exception_if_not_instanceof(mixed $value, string $class): void
    {
        if (! ($value instanceof $class)) {
            throw new InvalidTypeException($class, $value);
        }
    }
}
