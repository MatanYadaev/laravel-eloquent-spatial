<?php

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * @method Polygon[] getGeometries()
 */
class MultiPolygon extends GeometryCollection
{
    /** @var Collection<Polygon> */
    protected Collection $geometries;

    protected string $collectionOf = Polygon::class;

    protected int $minimumGeometries = 1;

    /**
     * @param Collection<Polygon>|Polygon[] $geometries
     * @throws InvalidArgumentException
     */
    public function __construct(Collection | array $geometries)
    {
        parent::__construct($geometries);
    }

    public function toWkt(): Expression
    {
        return DB::raw("MULTIPOLYGON({$this->toCollectionWkt()})");
    }
}
