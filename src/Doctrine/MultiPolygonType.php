<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/** @codeCoverageIgnore */
class MultiPolygonType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'multipolygon';
    }

    public function getName(): string
    {
        return 'multipolygon';
    }
}
