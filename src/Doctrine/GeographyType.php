<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class GeographyType extends Type
{
  public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
  {
    // @codeCoverageIgnoreStart
    return 'geography';
    // @codeCoverageIgnoreEnd
  }

  public function getName(): string
  {
    return 'geography';
  }
}
