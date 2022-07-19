<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use geoPHP;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use InvalidArgumentException;
use JsonException;
use JsonSerializable;
use MatanYadaev\EloquentSpatial\Factory;
use MatanYadaev\EloquentSpatial\GeometryCast;
use WKB as geoPHPWkb;

abstract class Geometry implements Castable, Arrayable, Jsonable, JsonSerializable
{
  public int $srid = 0;

  abstract public function toWkt(bool $withFunction = true): string;

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
    return $sridInBinary.(new geoPHPWkb)->write($geoPHPGeometry);
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

    $geometry = Factory::parse($wkb, true);
    $geometry->srid = $srid;

    if (! ($geometry instanceof static)) {
      throw new InvalidArgumentException(
        sprintf('Expected %s, %s given.', static::class, $geometry::class)
      );
    }

    return $geometry;
  }

  public static function fromWkt(string $wkt, int $srid = 0): static
  {
    $geometry = Factory::parse($wkt, false);
    $geometry->srid = $srid;

    if (! ($geometry instanceof static)) {
      throw new InvalidArgumentException(
        sprintf('Expected %s, %s given.', static::class, $geometry::class)
      );
    }

    return $geometry;
  }

  public static function fromJson(string $geoJson, int $srid = 0): static
  {
    $geometry = Factory::parse($geoJson, false);
    $geometry->srid = $srid;

    if (! ($geometry instanceof static)) {
      throw new InvalidArgumentException(
        sprintf('Expected %s, %s given.', static::class, $geometry::class)
      );
    }

    return $geometry;
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
}
