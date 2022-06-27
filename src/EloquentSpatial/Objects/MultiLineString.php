<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

/**
 * @method array<LineString> getGeometries()
 * @method LineString offsetGet(mixed $offset)
 * @extends GeometryCollection<LineString>
 */
class MultiLineString extends GeometryCollection
{
    protected string $collectionOf = LineString::class;

    protected int $minimumGeometries = 1;

    public function toWkt(): Expression
    {
        return DB::raw("MULTILINESTRING({$this->toCollectionWkt()})");
    }
}
