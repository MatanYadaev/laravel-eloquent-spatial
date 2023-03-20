<?php

use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Doctrine\GeometryCollectionType;
use MatanYadaev\EloquentSpatial\Doctrine\LineStringType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiLineStringType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiPointType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiPolygonType;
use MatanYadaev\EloquentSpatial\Doctrine\PointType;
use MatanYadaev\EloquentSpatial\Doctrine\PolygonType;

it('uses custom Doctrine types for spatial columns', function (string $column, string $typeClass, string $typeName): void {
  /** @var class-string<Type> $typeClass */
  $doctrineSchemaManager = DB::connection()->getDoctrineSchemaManager();

  $columns = $doctrineSchemaManager->listTableColumns('test_places');

  expect($columns[$column]->getType())->toBeInstanceOf($typeClass)
    ->and($columns[$column]->getType()->getName())->toBe($typeName);
})->with([
  'point' => ['point', PointType::class, 'point'],
  'line_string' => ['line_string', LineStringType::class, 'linestring'],
  'multi_point' => ['multi_point', MultiPointType::class, 'multipoint'],
  'polygon' => ['polygon', PolygonType::class, 'polygon'],
  'multi_line_string' => ['multi_line_string', MultiLineStringType::class, 'multilinestring'],
  'multi_polygon' => ['multi_polygon', MultiPolygonType::class, 'multipolygon'],
  'geometry_collection' => ['geometry_collection', GeometryCollectionType::class, 'geometrycollection'],
]);
