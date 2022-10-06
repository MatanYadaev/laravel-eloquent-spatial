<?php

use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Doctrine\GeometryCollectionType;
use MatanYadaev\EloquentSpatial\Doctrine\LineStringType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiLineStringType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiPointType;
use MatanYadaev\EloquentSpatial\Doctrine\MultiPolygonType;
use MatanYadaev\EloquentSpatial\Doctrine\PointType;
use MatanYadaev\EloquentSpatial\Doctrine\PolygonType;

it('uses custom Doctrine types for spatial columns', function (): void {
  $doctrineSchemaManager = DB::connection()->getDoctrineSchemaManager();

  $columns = $doctrineSchemaManager->listTableColumns('test_places');

  expect($columns['point']->getType())
    ->toBeInstanceOf(PointType::class)
    ->getName()->toBe('point');

  expect($columns['line_string']->getType())
    ->toBeInstanceOf(LineStringType::class)
    ->getName()->toBe('linestring');

  expect($columns['multi_point']->getType())
    ->toBeInstanceOf(MultiPointType::class)
    ->getName()->toBe('multipoint');

  expect($columns['polygon']->getType())
    ->toBeInstanceOf(PolygonType::class)
    ->getName()->toBe('polygon');

  expect($columns['multi_line_string']->getType())
    ->toBeInstanceOf(MultiLineStringType::class)
    ->getName()->toBe('multilinestring');

  expect($columns['multi_polygon']->getType())
    ->toBeInstanceOf(MultiPolygonType::class)
    ->getName()->toBe('multipolygon');

  expect($columns['geometry_collection']->getType())
    ->toBeInstanceOf(GeometryCollectionType::class)
    ->getName()->toBe('geometrycollection');
});
