<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use MatanYadaev\EloquentSpatial\Enums\Srid;

/**
 * @property Collection<int, Polygon> $geometries
 *
 * @method Collection<int, Polygon> getGeometries()
 * @method Polygon offsetGet(int $offset)
 * @method void offsetSet(int $offset, Polygon $value)
 */
class MultiPolygon extends GeometryCollection
{
    protected string $collectionOf = Polygon::class;

    protected int $minimumGeometries = 1;

    /**
     * @param  Collection<int, Polygon>|array<int, Polygon>  $geometries
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Collection|array $geometries, int|Srid $srid = 0)
    {
        // @phpstan-ignore-next-line
        parent::__construct($geometries, $this->srid = $srid instanceof Srid ? $srid->value : $srid);
    }

    public function toWkt(): string
    {
        $wktData = $this->getWktData();

        return "MULTIPOLYGON({$wktData})";
    }

    public function getWktData(): string
    {
        return $this->geometries
            ->map(static function (Polygon $polygon): string {
                $wktData = $polygon->getWktData();

                return "({$wktData})";
            })
            ->join(', ');
    }
}
