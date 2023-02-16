<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use MatanYadaev\EloquentSpatial\Objects\Geometry;

class GeometryCast implements CastsAttributes
{
  /** @var class-string<Geometry> */
  private string $className;

  /**
   * @param  class-string<Geometry>  $className
   */
  public function __construct(string $className)
  {
    $this->className = $className;
  }

  /**
   * @param  Model  $model
   * @param  string  $key
   * @param  string|ExpressionContract|null  $value
   * @param  array<string, mixed>  $attributes
   * @return Geometry|null
   */
  public function get($model, string $key, $value, array $attributes): ?Geometry
  {
    if (! $value) {
      return null;
    }

    if ($value instanceof ExpressionContract) {
      $wkt = $this->extractWktFromExpression($value, $model->getConnection());
      $srid = $this->extractSridFromExpression($value, $model->getConnection());

      return $this->className::fromWkt($wkt, $srid);
    }

    return $this->className::fromWkb($value);
  }

  /**
   * @param  Model  $model
   * @param  string  $key
   * @param  Geometry|mixed|null  $value
   * @param  array<string, mixed>  $attributes
   * @return ExpressionContract|null
   *
   * @throws InvalidArgumentException
   */
  public function set($model, string $key, $value, array $attributes): ExpressionContract|null
  {
    if (! $value) {
      return null;
    }

    if (is_array($value)) {
      $value = Geometry::fromArray($value);
    }

    if ($value instanceof ExpressionContract) {
      return $value;
    }

    if (! ($value instanceof $this->className)) {
      $geometryType = is_object($value) ? $value::class : gettype($value);
      throw new InvalidArgumentException(
        sprintf('Expected %s, %s given.', static::class, $geometryType)
      );
    }

    return $value->toSqlExpression($model->getConnection());
  }

  private function extractWktFromExpression(ExpressionContract $expression, Connection $connection): string
  {
    $grammar = $connection->getQueryGrammar();
    $expressionValue = $expression->getValue($grammar);

    preg_match('/ST_GeomFromText\(\'(.+)\', .+(, .+)?\)/', (string) $expressionValue, $match);

    return $match[1];
  }

  private function extractSridFromExpression(ExpressionContract $expression, Connection $connection): int
  {
    $grammar = $connection->getQueryGrammar();
    $expressionValue = $expression->getValue($grammar);

    preg_match('/ST_GeomFromText\(\'.+\', (.+)(, .+)?\)/', (string) $expressionValue, $match);

    return (int) $match[1];
  }
}
