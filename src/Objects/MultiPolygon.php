<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Objects;

/**
 * @method array<Polygon> getGeometries()
 * @method Polygon offsetGet(mixed $offset)
 *
 * @extends GeometryCollection<Polygon>
 */
class MultiPolygon extends GeometryCollection
{
    protected string $collectionOf = Polygon::class;

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

        return "MULTIPOLYGON({$wkt})";
    }
}
