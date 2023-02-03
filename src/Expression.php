<?php

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;
use Illuminate\Database\Grammar;
use Illuminate\Foundation\Application;

class Expression
{
  public static function getValue(ExpressionContract $expression, Grammar $grammar): string
  {
    $laravelVersion = Application::VERSION === '10.x-dev' ? '10.0.0' : Application::VERSION;

    if (version_compare($laravelVersion, '10.0.0', '<')) {
      return $expression->getValue();
    }

    return (string) $expression->getValue($grammar);
  }
}
