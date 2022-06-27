<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

/**
 * @method array<Polygon> getGeometries()
 * @method Polygon offsetGet(mixed $offset)
 * @extends GeometryCollection<Polygon>
 */
class MultiPolygon extends GeometryCollection
{
    protected string $collectionOf = Polygon::class;

    protected int $minimumGeometries = 1;

    public function toWkt(): Expression
    {
        return DB::raw("MULTIPOLYGON({$this->toCollectionWkt()})");
    }
}
