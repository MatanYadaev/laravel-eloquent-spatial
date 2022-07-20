<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use ArrayAccess;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;

class GeometryCollection extends Geometry implements ArrayAccess
{
  /** @var Collection<int, Geometry> */
  protected Collection $geometries;

  protected string $collectionOf = Geometry::class;

  protected int $minimumGeometries = 0;

  /**
   * @param  Collection<int, Geometry>|array<int, Geometry>  $geometries
   * @param  int  $srid
   *
   * @throws InvalidArgumentException
   */
  public function __construct(Collection|array $geometries, int $srid = 0)
  {
    if (is_array($geometries)) {
      $geometries = collect($geometries);
    }

    $this->geometries = $geometries;
    $this->srid = $srid;

    $this->validateGeometriesType();
    $this->validateGeometriesCount();
  }

  public function toWkt(): string
  {
    $wktData = $this->getWktData();

    return "GEOMETRYCOLLECTION({$wktData})";
  }

  public function getWktData(): string
  {
    return $this->geometries
      ->map(static function (Geometry $geometry): string {
        return $geometry->toWkt();
      })
      ->join(', ');
  }

  /**
   * @return array<mixed>
   */
  public function getCoordinates(): array
  {
    return $this->geometries
      ->map(static function (Geometry $geometry): array {
        return $geometry->getCoordinates();
      })
      ->all();
  }

  /**
   * @return array<mixed>
   */
  public function toArray(): array
  {
    if ($this->isExtended()) {
      return parent::toArray();
    }

    return [
      'type' => class_basename(static::class),
      'geometries' => $this->geometries->map(static function (Geometry $geometry): array {
        return $geometry->toArray();
      }),
    ];
  }

  /**
   * @return Collection<int, Geometry>
   */
  public function getGeometries(): Collection
  {
    return new Collection($this->geometries->all());
  }

  /**
   * @param  int  $offset
   * @return bool
   */
  public function offsetExists($offset): bool
  {
    return isset($this->geometries[$offset]);
  }

  /**
   * @param  int  $offset
   * @return Geometry
   */
  public function offsetGet($offset): Geometry
  {
    // @phpstan-ignore-next-line
    return $this->geometries[$offset];
  }

  /**
   * @param  int  $offset
   * @param  Geometry  $value
   */
  public function offsetSet($offset, $value): void
  {
    $this->geometries[$offset] = $value;
    $this->validateGeometriesType();
  }

  /**
   * @param  int  $offset
   */
  public function offsetUnset($offset): void
  {
    $this->geometries->splice($offset, 1);
    $this->validateGeometriesCount();
  }

  /**
   * @throws InvalidArgumentException
   */
  protected function validateGeometriesCount(): void
  {
    $geometriesCount = $this->geometries->count();
    if ($geometriesCount < $this->minimumGeometries) {
      throw new InvalidArgumentException(
        sprintf(
          '%s must contain at least %s %s',
          static::class,
          $this->minimumGeometries,
          Str::plural('entries', $geometriesCount)
        )
      );
    }
  }

  /**
   * @throws InvalidArgumentException
   */
  protected function validateGeometriesType(): void
  {
    $this->geometries->each(function (mixed $geometry): void {
      /** @var mixed $geometry */
      if (! is_object($geometry) || ! ($geometry instanceof $this->collectionOf)) {
        throw new InvalidArgumentException(
          sprintf('%s must be a collection of %s', static::class, $this->collectionOf)
        );
      }
    });
  }

  /**
   * Checks whether the class is used directly or via a sub-class.
   *
   * @return bool
   */
  private function isExtended(): bool
  {
    return is_subclass_of(static::class, self::class);
  }
}
