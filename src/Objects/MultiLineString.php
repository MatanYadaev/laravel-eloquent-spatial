<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use MatanYadaev\EloquentSpatial\Enums\Srid;

/**
 * @property Collection<int, LineString> $geometries
 *
 * @method Collection<int, LineString> getGeometries()
 * @method LineString offsetGet(int $offset)
 * @method void offsetSet(int $offset, LineString $value)
 */
class MultiLineString extends GeometryCollection
{
    protected string $collectionOf = LineString::class;

    protected int $minimumGeometries = 1;

    /**
     * @param  Collection<int, LineString>|array<int, LineString>  $geometries
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

        return "MULTILINESTRING({$wktData})";
    }

    public function getWktData(): string
    {
        return $this->geometries
            ->map(static function (LineString $lineString): string {
                $wktData = $lineString->getWktData();

                return "({$wktData})";
            })
            ->join(', ');
    }
}
