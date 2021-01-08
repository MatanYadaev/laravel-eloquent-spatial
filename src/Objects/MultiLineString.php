<?php

namespace MatanYadaev\EloquentSpatial\Objects;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @method LineString[] getGeometries()
 */
class MultiLineString extends GeometryCollection
{
    protected string $collectionOf = LineString::class;

    protected int $minimumGeometries = 1;

    /**
     * @param Collection<LineString>|LineString[] $geometries
     * @param int|null $srid
     */
    public function __construct(Collection|array $geometries, ?int $srid = 0)
    {
        parent::__construct($geometries, $srid);
    }

    public function toWkt(): Expression
    {
        $collectionWkt = $this->toCollectionWkt();
        $expression = DB::raw("MULTILINESTRING({$collectionWkt})");

        if ($this->srid) {
            $expression = DB::raw("ST_SRID({$expression}, {$this->srid})");
        }

        return $expression;
    }
}
