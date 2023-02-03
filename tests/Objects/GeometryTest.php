<?php

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\AxisOrder;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

it('throws exception when generating geometry from other geometry WKB', function (): void {
  expect(function (): void {
    $pointWkb = (new Point(0, 180))->toWkb();

    LineString::fromWkb($pointWkb);
  })->toThrow(InvalidArgumentException::class);
});

it('throws exception when generating geometry with invalid latitude', function (): void {
  expect(function (): void {
    $point = (new Point(91, 0, 4326));
    TestPlace::factory()->create(['point' => $point]);
  })->toThrow(QueryException::class);
})->skip(fn () => ! (new AxisOrder)->supported(DB::connection()));

it('throws exception when generating geometry with invalid latitude - without axis-order', function (): void {
  expect(function (): void {
    $point = (new Point(91, 0, 4326));
    TestPlace::factory()->create(['point' => $point]);

    TestPlace::query()
      ->withDistanceSphere('point', new Point(1, 1, 4326))
      ->firstOrFail();
  })->toThrow(QueryException::class);
})->skip(fn () => (new AxisOrder)->supported(DB::connection()));

it('throws exception when generating geometry with invalid longitude', function (): void {
  expect(function (): void {
    $point = (new Point(0, 181, 4326));
    TestPlace::factory()->create(['point' => $point]);
  })->toThrow(QueryException::class);
})->skip(fn () => ! (new AxisOrder)->supported(DB::connection()));

it('throws exception when generating geometry with invalid longitude - without axis-order', function (): void {
  expect(function (): void {
    $point = (new Point(0, 181, 4326));
    TestPlace::factory()->create(['point' => $point]);

    TestPlace::query()
      ->withDistanceSphere('point', new Point(1, 1, 4326))
      ->firstOrFail();
  })->toThrow(QueryException::class);
})->skip(fn () => (new AxisOrder)->supported(DB::connection()));

it('throws exception when generating geometry from other geometry WKT', function (): void {
  expect(function (): void {
    $pointWkt = 'POINT(180 0)';

    LineString::fromWkt($pointWkt);
  })->toThrow(InvalidArgumentException::class);
});

it('throws exception when generating geometry from non-JSON', function (): void {
  expect(function (): void {
    Point::fromJson('invalid-value');
  })->toThrow(InvalidArgumentException::class);
});

it('throws exception when generating geometry from empty JSON', function (): void {
  expect(function (): void {
    Point::fromJson('{}');
  })->toThrow(InvalidArgumentException::class);
});

it('throws exception when generating geometry from other geometry JSON', function (): void {
  expect(function (): void {
    $pointJson = '{"type":"Point","coordinates":[0,180]}';

    LineString::fromJson($pointJson);
  })->toThrow(InvalidArgumentException::class);
});

it('creates an SQL expression from a geometry', function (): void {
  $point = new Point(0, 180, 4326);

  $sqlExpression = $point->toSqlExpression(DB::connection());

  $grammar = DB::query()->getGrammar();
  $sqlExpression = $sqlExpression->getValue($grammar);
  expect($sqlExpression)->toEqual("ST_GeomFromText('POINT(180 0)', 4326, 'axis-order=long-lat')");
})->skip(fn () => ! (new AxisOrder)->supported(DB::connection()));

it('creates an SQL expression from a geometry - without axis-order', function (): void {
  $point = new Point(0, 180, 4326);

  $sqlExpression = $point->toSqlExpression(DB::connection());

  $grammar = DB::query()->getGrammar();
  $sqlExpression = $sqlExpression->getValue($grammar);
  expect($sqlExpression)->toEqual("ST_GeomFromText('POINT(180 0)', 4326)");
})->skip(fn () => (new AxisOrder)->supported(DB::connection()));

it('creates a geometry object from a geo json array', function (): void {
  $point = new Point(0, 180);
  $pointGeoJsonArray = $point->toArray();

  $geometryCollectionFromArray = Point::fromArray($pointGeoJsonArray);

  expect($geometryCollectionFromArray)->toEqual($point);
});

it('throws exception when creating a geometry object from an invalid geo json array', function (): void {
  $invalidPointGeoJsonArray = [
    'type' => 'InvalidGeometryType',
    'coordinates' => [0, 180],
  ];

  expect(function () use ($invalidPointGeoJsonArray): void {
    Geometry::fromArray($invalidPointGeoJsonArray);
  })->toThrow(InvalidArgumentException::class);
});

it('throws exception when creating a geometry object from another geometry geo json array', function (): void {
  $pointGeoJsonArray = [
    'type' => 'Point',
    'coordinates' => [0, 180],
  ];

  expect(function () use ($pointGeoJsonArray): void {
    LineString::fromArray($pointGeoJsonArray);
  })->toThrow(InvalidArgumentException::class);
});
