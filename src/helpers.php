<?php

declare(strict_types=1);

if (! function_exists('is_json')) {
    function is_json(string $string): bool
    {
        json_decode($string);

        return json_last_error() === JSON_ERROR_NONE;
    }
}
