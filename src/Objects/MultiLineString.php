<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

/**
 * @method array<LineString> getGeometries()
 * @method LineString offsetGet(mixed $offset)
 *
 * @extends GeometryCollection<LineString>
 */
class MultiLineString extends GeometryCollection
{
    protected string $collectionOf = LineString::class;

    protected int $minimumGeometries = 1;

    /**
     * @param  bool  $withFunction
     * @return string
     *
     * @phpcsSuppress SlevomatCodingStandard.Functions.UnusedParameter
     */
    public function toWkt(bool $withFunction = true): string
    {
        $wkt = $this->toCollectionWkt(withFunction: false);

        return "MULTILINESTRING({$wkt})";
    }
}
