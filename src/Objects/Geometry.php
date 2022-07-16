<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use InvalidArgumentException;
use JsonException;
use JsonSerializable;
use MatanYadaev\EloquentSpatial\Factory;
use MatanYadaev\EloquentSpatial\GeometryCast;

abstract class Geometry implements Castable, Arrayable, Jsonable, JsonSerializable
{
    protected int $srid = 0;

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

    /**
     * @param  string  $wkb
     * @return static
     *
     * @throws InvalidArgumentException
     */
    public static function fromWkb(string $wkb): static
    {
        $string = substr($wkb, 0, 4);
        $string = unpack('L', $string);
        $srid = is_array($string) ? (int) $string[1] : 0;

        $geometry = Factory::parse($wkb, true);

        if (! ($geometry instanceof static)) {
            throw new InvalidArgumentException(
                sprintf('Expected %s, %s given.', static::class, $geometry::class)
            );
        }
        if ($srid) {
            $geometry->setSrid($srid);
        }

        return $geometry;
    }

    /**
     * @param  string  $wkt
     * @param  int|null  $srid
     * @return static
     *
     * @throws InvalidArgumentException
     */
    public static function fromWkt(string $wkt, ?int $srid = null): static
    {
        $geometry = Factory::parse($wkt, false);

        if (! ($geometry instanceof static)) {
            throw new InvalidArgumentException(
                sprintf('Expected %s, %s given.', static::class, $geometry::class)
            );
        }

        if ($srid) {
            $geometry->setSrid($srid);
        }

        return $geometry;
    }

    /**
     * @param  string  $geoJson
     * @param  int|null  $srid
     * @return static
     *
     * @throws InvalidArgumentException
     */
    public static function fromJson(string $geoJson, ?int $srid = null): static
    {
        $geometry = Factory::parse($geoJson, false);

        if (! ($geometry instanceof static)) {
            throw new InvalidArgumentException(
                sprintf('Expected %s, %s given.', static::class, $geometry::class)
            );
        }
        if ($srid) {
            $geometry->setSrid($srid);
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
            /** @var GeometryCollection<Geometry> $this */
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
     * @param  int  $srid
     * @return $this
     */
    public function setSrid(int $srid): self
    {
        $this->srid = $srid;

        return $this;
    }

    public function getSrid(): int
    {
        return $this->srid;
    }

    /**
     * @param  int|null  $srid
     * @return void
     * setDefaultSrid for get default SRID if exists in mysql config
     */
    protected function setDefaultSrid(?int $srid = null): void
    {
        if ($srid) {
            $this->srid = $srid;

            return;
        }
        if (config('database.connections.mysql.default_srid')) {
            $this->srid = intval(config('database.connections.mysql.default_srid'));
        }
    }
}
