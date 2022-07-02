<?php

namespace MatanYadaev\EloquentSpatial\Utils;

use JsonException;

class Helpers
{
    /**
     * Determine if a given string is valid JSON.
     * Credit: Laravel - Illuminate/Support.
     *
     * @param  string  $value
     * @return bool
     */
    public static function isJson($value)
    {
        if (! is_string($value)) {
            return false;
        }

        try {
            json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        }
        catch (JsonException) {
            return false;
        }

        return true;
    }
}
