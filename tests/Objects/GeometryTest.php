<?php

use Illuminate\Database\PostgresConnection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\AxisOrder;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\GeometryExpression;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\GeometryCollection;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
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
        $point = (new Point(91, 0, Srid::WGS84->value));
        TestPlace::factory()->create(['point' => $point]);
    })->toThrow(QueryException::class);
})->skip(fn () => ! AxisOrder::supported(DB::connection()));

it('throws exception when generating geometry with invalid latitude - without axis-order', function (): void {
    expect(function (): void {
        $point = (new Point(91, 0, Srid::WGS84->value));
        TestPlace::factory()->create(['point' => $point]);

        TestPlace::query()
            ->withDistanceSphere('point', new Point(1, 1, Srid::WGS84->value))
            ->firstOrFail();
    })->toThrow(QueryException::class);
})->skip(fn () => AxisOrder::supported(DB::connection()) || DB::connection() instanceof PostgresConnection);

it('throws exception when generating geometry with invalid longitude', function (): void {
    expect(function (): void {
        $point = (new Point(0, 181, Srid::WGS84->value));
        TestPlace::factory()->create(['point' => $point]);
    })->toThrow(QueryException::class);
})->skip(fn () => ! AxisOrder::supported(DB::connection()));

it('throws exception when generating geometry with invalid longitude - without axis-order', function (): void {
    expect(function (): void {
        $point = (new Point(0, 181, Srid::WGS84->value));
        TestPlace::factory()->create(['point' => $point]);

        TestPlace::query()
            ->withDistanceSphere('point', new Point(1, 1, Srid::WGS84->value))
            ->firstOrFail();
    })->toThrow(QueryException::class);
})->skip(fn () => AxisOrder::supported(DB::connection()) || DB::connection() instanceof PostgresConnection);

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
    $point = new Point(0, 180, Srid::WGS84->value);

    $expression = $point->toSqlExpression(DB::connection());

    $grammar = DB::getQueryGrammar();
    $expressionValue = $expression->getValue($grammar);
    expect($expressionValue)->toEqual("ST_GeomFromText('POINT(180 0)', 4326, 'axis-order=long-lat')");
})->skip(fn () => ! AxisOrder::supported(DB::connection()));

it('creates an SQL expression from a geometry - without axis-order', function (): void {
    $point = new Point(0, 180, Srid::WGS84->value);

    $expression = $point->toSqlExpression(DB::connection());

    $grammar = DB::getQueryGrammar();
    $expressionValue = $expression->getValue($grammar);
    expect($expressionValue)->toEqual(
        (new GeometryExpression("ST_GeomFromText('POINT(180 0)', 4326)"))->normalize(DB::connection())
    );
})->skip(fn () => AxisOrder::supported(DB::connection()));

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

it('creates a model record with geometry (point)', function (): void {
    // Arrange
    $point = Point::fromJson('{"type":"Point","coordinates":[0,180]}');

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['geometry' => $point]);

    // Assert
    expect($testPlace->geometry)->toBeInstanceOf(Point::class);
    expect($testPlace->geometry)->toEqual($point);
});

it('creates a model record with geometry (line string)', function (): void {
    // Arrange
    $lineString = LineString::fromJson('{"type":"LineString","coordinates":[[180,0],[179,1]]}');

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['geometry' => $lineString]);

    // Assert
    expect($testPlace->geometry)->toBeInstanceOf(LineString::class);
    expect($testPlace->geometry)->toEqual($lineString);
});

it('creates a model record with geometry (multi point)', function (): void {
    // Arrange
    $multiPoint = MultiPoint::fromJson('{"type":"MultiPoint","coordinates":[[180,0],[179,1]]}');

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['geometry' => $multiPoint]);

    // Assert
    expect($testPlace->geometry)->toBeInstanceOf(MultiPoint::class);
    expect($testPlace->geometry)->toEqual($multiPoint);
});

it('creates a model record with geometry (multi line string)', function (): void {
    // Arrange
    $multiLineString = MultiLineString::fromJson('{"type":"MultiLineString","coordinates":[[[180,0],[179,1]]]}');

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['geometry' => $multiLineString]);

    // Assert
    expect($testPlace->geometry)->toBeInstanceOf(MultiLineString::class);
    expect($testPlace->geometry)->toEqual($multiLineString);
});

it('creates a model record with geometry (polygon)', function (): void {
    // Arrange
    $polygon = Polygon::fromJson('{"type":"Polygon","coordinates":[[[180,0],[179,1],[180,1],[180,0]]]}');

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['geometry' => $polygon]);

    // Assert
    expect($testPlace->geometry)->toBeInstanceOf(Polygon::class);
    expect($testPlace->geometry)->toEqual($polygon);
});

it('creates a model record with geometry (multi polygon)', function (): void {
    // Arrange
    $multiPolygon = MultiPolygon::fromJson('{"type":"MultiPolygon","coordinates":[[[[180,0],[179,1],[180,1],[180,0]]]]}');

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['geometry' => $multiPolygon]);

    // Assert
    expect($testPlace->geometry)->toBeInstanceOf(MultiPolygon::class);
    expect($testPlace->geometry)->toEqual($multiPolygon);
});

it('creates a model record with geometry (geometry collection)', function (): void {
    // Arrange
    $geometryCollection = GeometryCollection::fromJson('{"type":"GeometryCollection","geometries":[{"type":"Point","coordinates":[0,180]}]}');

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['geometry' => $geometryCollection]);

    // Assert
    expect($testPlace->geometry)->toBeInstanceOf(GeometryCollection::class);
    expect($testPlace->geometry)->toEqual($geometryCollection);
});
