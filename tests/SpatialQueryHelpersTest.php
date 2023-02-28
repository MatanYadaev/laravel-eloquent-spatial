<?php

use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

it('toExpressionString can handle a Expression input', function (): void {
  $spatialBuilder = TestPlace::query();
  $toExpressionStringMethod = (new ReflectionClass($spatialBuilder))->getMethod('toExpressionString');

  $result = $toExpressionStringMethod->invoke($spatialBuilder, DB::raw('POINT(longitude, latitude)'));

  expect($result)->toBe('POINT(longitude, latitude)');
});

it('toExpressionString can handle a Geometry input', function (): void {
  $spatialBuilder = TestPlace::query();
  $toExpressionStringMethod = (new ReflectionClass($spatialBuilder))->getMethod('toExpressionString');
  $polygon = Polygon::fromJson('{"type":"Polygon","coordinates":[[[-1,-1],[1,-1],[1,1],[-1,1],[-1,-1]]]}');

  $result = $toExpressionStringMethod->invoke($spatialBuilder, $polygon);

  $grammar = $spatialBuilder->getGrammar();
  $connection = $spatialBuilder->getConnection();
  $sqlSerializedPolygon = $polygon->toSqlExpression($connection)->getValue($grammar);
  expect($result)->toBe($sqlSerializedPolygon);
});

it('toExpressionString can handle a string input', function (): void {
  $spatialBuilder = TestPlace::query();
  $toExpressionStringMethod = (new ReflectionClass($spatialBuilder))->getMethod('toExpressionString');

  $result = $toExpressionStringMethod->invoke($spatialBuilder, 'test_places.point');

  expect($result)->toBe('`test_places`.`point`');
});
