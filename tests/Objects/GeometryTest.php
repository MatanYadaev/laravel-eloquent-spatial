<?php

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\AxisOrder;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\GeometryCollection;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
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
})->skip(fn () =>  (new AxisOrder)->supported(DB::connection()));

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

it('creates geometry collection from array (serialized model)', function (): void {
  $geometryCollection = new GeometryCollection([
    new Polygon([
      new LineString([
        new Point(0, 180),
        new Point(1, 179),
        new Point(2, 178),
        new Point(3, 177),
        new Point(0, 180),
      ]),
    ]),
    new Point(0, 180),
  ]);
  $geometryCollectionArray = $geometryCollection->toArray();

  $geometryCollectionFromArray = GeometryCollection::fromArray($geometryCollectionArray);

  expect($geometryCollectionFromArray)->toEqual($geometryCollection);
});

it('throws exception when generating geometry from wrong array', function (): void {
  expect(function (): void {
    $geometryCollectionArray = [
      'type' => 'Point2',
      'coordinates' => [0, 180],
    ];

    Geometry::fromArray($geometryCollectionArray);
  })->toThrow(InvalidArgumentException::class);
});

it('throws exception when generating geometry from other geometry array', function (): void {
  expect(function (): void {
    $geometryCollectionArray = [
      'type' => 'Point',
      'coordinates' => [0, 180],
    ];

    LineString::fromArray($geometryCollectionArray);
  })->toThrow(InvalidArgumentException::class);
});
