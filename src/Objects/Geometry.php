<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use geoPHP;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Query\Expression as ExpressionContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use JsonException;
use JsonSerializable;
use MatanYadaev\EloquentSpatial\AxisOrder;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Factory;
use MatanYadaev\EloquentSpatial\GeometryCast;
use MatanYadaev\EloquentSpatial\GeometryExpression;
use Stringable;
use WKB as geoPHPWkb;

abstract class Geometry implements Arrayable, Castable, Jsonable, JsonSerializable, Stringable
{
    use Macroable;

    public int $srid = 0;

    abstract public function toWkt(): string;

    abstract public function getWktData(): string;

    public function __toString(): string
    {
        return $this->toWkt();
    }

    /**
     * @param  int  $options
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

    public static function fromWkb(string $wkb): static
    {
        if (ctype_xdigit($wkb)) {
            // @codeCoverageIgnoreStart
            $geometry = Factory::parse($wkb);
            // @codeCoverageIgnoreEnd
        } else {
            $srid = substr($wkb, 0, 4);
            // @phpstan-ignore-next-line
            $srid = unpack('L', $srid)[1];

            $wkb = substr($wkb, 4);

            $geometry = Factory::parse($wkb);
            $geometry->srid = $srid;
        }

        if (! ($geometry instanceof static)) {
            throw new InvalidArgumentException(
                sprintf('Expected %s, %s given.', static::class, $geometry::class)
            );
        }

        return $geometry;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromWkt(string $wkt, int|Srid $srid = 0): static
    {
        $geometry = Factory::parse($wkt);
        $geometry->srid = $srid instanceof Srid ? $srid->value : $srid;

        if (! ($geometry instanceof static)) {
            throw new InvalidArgumentException(
                sprintf('Expected %s, %s given.', static::class, $geometry::class)
            );
        }

        return $geometry;
    }

    /**
     * @throws InvalidArgumentException
     */
    public static function fromJson(string $geoJson, int|Srid $srid = 0): static
    {
        $geometry = Factory::parse($geoJson);
        $geometry->srid = $srid instanceof Srid ? $srid->value : $srid;

        if (! ($geometry instanceof static)) {
            throw new InvalidArgumentException(
                sprintf('Expected %s, %s given.', static::class, $geometry::class)
            );
        }

        return $geometry;
    }

    /**
     * @param  array<mixed>  $geometry
     *
     * @throws JsonException
     */
    public static function fromArray(array $geometry, int|Srid $srid = 0): static
    {
        $geoJson = json_encode($geometry, JSON_THROW_ON_ERROR);

        return static::fromJson($geoJson, $srid);
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
     */
    public static function castUsing(array $arguments): CastsAttributes
    {
        return new GeometryCast(static::class);
    }

    public function toSqlExpression(ConnectionInterface $connection): ExpressionContract
    {
        $wkt = $this->toWkt();

        if (! AxisOrder::supported($connection)) {
            // @codeCoverageIgnoreStart
            return DB::raw((new GeometryExpression("ST_GeomFromText('{$wkt}', {$this->srid})"))->normalize($connection));
            // @codeCoverageIgnoreEnd
        }

        return DB::raw((new GeometryExpression("ST_GeomFromText('{$wkt}', {$this->srid}, 'axis-order=long-lat')"))->normalize($connection));
    }
}
