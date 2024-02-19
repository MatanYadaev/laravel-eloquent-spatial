<?php

use Doctrine\DBAL\Types\Type;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Doctrine\GeometryCollectionType;
use MatanYadaev\EloquentSpatial\Doctrine\GeographyType;
use MatanYadaev\EloquentSpatial\Doctrine\LineStringType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiLineStringType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiPointType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiPolygonType;
use MatanYadaev\EloquentSpatial\Doctrine\PointType;
use MatanYadaev\EloquentSpatial\Doctrine\PolygonType;

/** @var array{column: string, postgresType: class-string<Type>, mySqlType: class-string<Type>} $typeClass */
$dataset = [
  [
    'column' => 'point',
    'postgresType' => GeographyType::class,
    'mySqlType' => PointType::class,
  ],
  [
    'column' => 'line_string',
    'postgresType' => GeographyType::class,
    'mySqlType' => LineStringType::class,
  ],
  [
    'column' => 'multi_point',
    'postgresType' => GeographyType::class,
    'mySqlType' => MultiPointType::class,
  ],
  [
    'column' => 'polygon',
    'postgresType' => GeographyType::class,
    'mySqlType' => PolygonType::class,
  ],
  [
    'column' => 'multi_line_string',
    'postgresType' => GeographyType::class,
    'mySqlType' => MultiLineStringType::class,
  ],
  [
    'column' => 'multi_polygon',
    'postgresType' => GeographyType::class,
    'mySqlType' => MultiPolygonType::class,
  ],
  [
    'column' => 'geometry_collection',
    'postgresType' => GeographyType::class,
    'mySqlType' => GeometryCollectionType::class,
  ],
];

it('uses custom Doctrine types for spatial columns', function ($column, $postgresType, $mySqlType): void {
  $doctrineSchemaManager = DB::connection()->getDoctrineSchemaManager();

  $columns = $doctrineSchemaManager->listTableColumns('test_places');

  expect($columns[$column]->getType())->toBeInstanceOfOnPostgres($postgresType)->toBeInstanceOfOnMysql($mySqlType);
})->with($dataset);
