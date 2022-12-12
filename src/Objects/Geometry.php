<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use geoPHP;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use JsonException;
use JsonSerializable;
use MatanYadaev\EloquentSpatial\AxisOrder;
use MatanYadaev\EloquentSpatial\Factory;
use MatanYadaev\EloquentSpatial\GeometryCast;
use Stringable;
use WKB as geoPHPWkb;

abstract class Geometry implements Castable, Arrayable, Jsonable, JsonSerializable, Stringable
{
  use Macroable;

  public int $srid = 0;

  abstract public function toWkt(): string;

  abstract public function getWktData(): string;

  /**
   * @return string
   */
  public function __toString(): string
  {
    return $this->toWkt();
  }

  /**
   * @param  int  $options
   * @return string
   *
   * @throws JsonException
   */
  public function toJson($options = 0): string
  {
    return json_encode($this, $options | JSON_THROW_ON_ERROR);
  }

  public function toWkb(): string
  {
    $geoPHPGeometry = geoPHP::load($this->toJson());

    $sridInBinary = pack('L', $this->srid);

    // @phpstan-ignore-next-line
    $wkbWithoutSrid = (new geoPHPWkb)->write($geoPHPGeometry);

    return $sridInBinary.$wkbWithoutSrid;
  }

  /**
   * @param  string  $wkb
   * @return static
   *
   * @throws InvalidArgumentException
   */
  public static function fromWkb(string $wkb): static
  {
    $srid = substr($wkb, 0, 4);
    // @phpstan-ignore-next-line
    $srid = unpack('L', $srid)[1];

    $wkb = substr($wkb, 4);

    $geometry = Factory::parse($wkb);
    $geometry->srid = $srid;

    if (! ($geometry instanceof static)) {
      throw new InvalidArgumentException(
        sprintf('Expected %s, %s given.', static::class, $geometry::class)
      );
    }

    return $geometry;
  }

  /**
   * @param  string  $wkt
   * @param  int  $srid
   * @return static
   *
   * @throws InvalidArgumentException
   */
  public static function fromWkt(string $wkt, int $srid = 0): static
  {
    $geometry = Factory::parse($wkt);
    $geometry->srid = $srid;

    if (! ($geometry instanceof static)) {
      throw new InvalidArgumentException(
        sprintf('Expected %s, %s given.', static::class, $geometry::class)
      );
    }

    return $geometry;
  }

  /**
   * @param  string  $geoJson
   * @param  int  $srid
   * @return static
   *
   * @throws InvalidArgumentException
   */
  public static function fromJson(string $geoJson, int $srid = 0): static
  {
    $geometry = Factory::parse($geoJson);
    $geometry->srid = $srid;

    if (! ($geometry instanceof static)) {
      throw new InvalidArgumentException(
        sprintf('Expected %s, %s given.', static::class, $geometry::class)
      );
    }

    return $geometry;
  }

  /**
   * @param  array<mixed>  $geometry
   * @return static
   *
   * @throws JsonException
   */
  public static function fromArray(array $geometry): static
  {
    $geoJson = json_encode($geometry, JSON_THROW_ON_ERROR);

    return static::fromJson($geoJson);
  }

  /**
   * @return array<mixed>
   */
  public function jsonSerialize(): array
  {
    return $this->toArray();
  }

  /**
   * @return array{type: string, coordinates: array<mixed>}
   */
  public function toArray(): array
  {
    return [
      'type' => class_basename(static::class),
      'coordinates' => $this->getCoordinates(),
    ];
  }

  /**
   * @return string
   *
   * @throws JsonException
   */
  public function toFeatureCollectionJson(): string
  {
    if (static::class === GeometryCollection::class) {
      /** @var GeometryCollection $this */
      $geometries = $this->geometries;
    } else {
      $geometries = collect([$this]);
    }

    $features = $geometries->map(static function (self $geometry): array {
      return [
        'type' => 'Feature',
        'properties' => [],
        'geometry' => $geometry->toArray(),
      ];
    });

    return json_encode(
      [
        'type' => 'FeatureCollection',
        'features' => $features,
      ],
      JSON_THROW_ON_ERROR
    );
  }

  /**
   * @return array<mixed>
   */
  abstract public function getCoordinates(): array;

  /**
   * @param  array<string>  $arguments
   * @return CastsAttributes
   */
  public static function castUsing(array $arguments): CastsAttributes
  {
    return new GeometryCast(static::class);
  }

  /**
   * @param  ConnectionInterface  $connection
   * @return Expression
   */
  public function toSqlExpression(ConnectionInterface $connection): Expression
  {
    $wkt = $this->toWkt();

    if (! (new AxisOrder)->supported($connection)) {
      // @codeCoverageIgnoreStart
      return DB::raw("ST_GeomFromText('{$wkt}', {$this->srid})");
      // @codeCoverageIgnoreEnd
    }

    return DB::raw("ST_GeomFromText('{$wkt}', {$this->srid}, 'axis-order=long-lat')");
  }
}
