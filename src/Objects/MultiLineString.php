<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @method array<LineString> getGeometries()
 * @method LineString offsetGet(mixed $offset)
 */
class MultiLineString extends GeometryCollection
{
    /** @var Collection<LineString> */
    protected Collection $geometries;

    protected string $collectionOf = LineString::class;

    protected int $minimumGeometries = 1;

    /**
     * @param Collection<LineString>|array<LineString> $geometries
     */
    public function __construct(Collection | array $geometries)
    {
        parent::__construct($geometries);
    }

    public function toWkt(): Expression
    {
        return DB::raw("MULTILINESTRING({$this->toCollectionWkt()})");
    }
}
