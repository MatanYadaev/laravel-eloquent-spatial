<?php

use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\AxisOrder;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\GeometryExpression;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

it('creates a model record with null geometry', function (): void {
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['point' => null]);

    expect($testPlace->point)->toBeNull();
});

it('updates a model record', function (): void {
    $point = new Point(0, 180);
    $point2 = new Point(0, 0);
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['point' => $point]);

    $testPlace->update(['point' => $point2]);

    expect($testPlace->point)->not->toEqual($point);
    expect($testPlace->point)->toEqual($point2);
});

it('updates a model record with expression', function (): void {
    $point = new Point(0, 180);
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['point' => $point]);
    $pointFromAttributes = $testPlace->getAttributes()['point'];

    expect(function () use ($testPlace, $pointFromAttributes): void {
        $testPlace->update(['point' => $pointFromAttributes]);
    })->not->toThrow(InvalidArgumentException::class);
});

it('updates a model record with null geometry', function (): void {
    $point = new Point(0, 180);
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['point' => $point]);

    $testPlace->update(['point' => null]);

    expect($testPlace->point)->toBeNull();
});

it('gets original geometry field', function (): void {
    $point = new Point(0, 180, Srid::WGS84->value);
    $point2 = new Point(0, 0, Srid::WGS84->value);
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['point' => $point]);

    $testPlace->point = $point2;

    expect($testPlace->getOriginal('point'))->toEqual($point);
    expect($testPlace->point)->not->toEqual($point);
    expect($testPlace->point)->toEqual($point2);
});

it('serializes a model record to array with geometry', function (): void {
    $point = new Point(0, 180);
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['point' => $point]);

    $serialized = $testPlace->toArray();

    $expectedArray = $point->toArray();
    expect($serialized['point'])->toEqual($expectedArray);
});

it('serializes a model record to json with geometry', function (): void {
    $point = new Point(0, 180);
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['point' => $point]);

    $serialized = $testPlace->toJson();

    // @phpstan-ignore-next-line
    $json = json_encode(json_decode($serialized, true)['point']);
    $expectedJson = $point->toJson();
    expect($json)->toBe($expectedJson);
});

it('throws exception when cast serializing incorrect geometry object', function (): void {
    expect(function (): void {
        TestPlace::factory()->make([
            'point' => new LineString([
                new Point(0, 180),
                new Point(1, 179),
            ]),
        ]);
    })->toThrow(InvalidArgumentException::class);
});

it('throws exception when cast serializing non-geometry object', function (): void {
    expect(function (): void {
        TestPlace::factory()->make([
            'point' => 'not-a-point-object',
        ]);
    })->toThrow(InvalidArgumentException::class);
});

it('throws exception when cast deserializing incorrect geometry object', function (): void {
    TestPlace::insert(array_merge(TestPlace::factory()->definition(), [
        'point_with_line_string_cast' => DB::raw(
            (new GeometryExpression('POINT(0, 180)'))->normalize(DB::connection())
        ),
    ]));
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::firstOrFail();

    expect(function () use ($testPlace): void {
        $testPlace->getAttribute('point_with_line_string_cast');
    })->toThrow(InvalidArgumentException::class);
});

it('creates a model record with geometry from geo json array', function (): void {
    $point = new Point(0, 180);
    $pointGeoJsonArray = $point->toArray();

    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->make(['point' => $pointGeoJsonArray]);

    expect($testPlace->point)->toEqual($point);
});

it('checks a model record is not dirty after creation', function (): void {
    $point = new Point(0, 180);

    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['point' => $point]);

    expect($testPlace->isDirty())->toBeFalse();
});

it('checks a model record is not dirty after fetch', function (): void {
    $point = new Point(0, 180);
    TestPlace::factory()->create(['point' => $point]);

    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::firstOrFail();

    expect($testPlace->isDirty())->toBeFalse();
});

it('checks a model record is dirty after update from null before save', function (): void {
    $point = new Point(0, 180);
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create([]);

    $testPlace->point = $point;

    expect($testPlace->isDirty())->toBeTrue();
});

it('checks a model record is dirty after update before save', function (): void {
    $point = new Point(0, 180);
    $point2 = new Point(0, 0);
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['point' => $point]);

    $testPlace->point = $point2;

    expect($testPlace->isDirty())->toBeTrue();
});

it('checks a model record is not dirty after update and save', function (): void {
    $point = new Point(0, 180);
    $point2 = new Point(0, 0);
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['point' => $point]);

    $testPlace->point = $point2;
    $testPlace->save();

    expect($testPlace->isDirty())->toBeFalse();
});

it('checks a model record is not dirty after update to same value before save', function (): void {
    $point = new Point(0, 180);
    $point2 = new Point(0, 180);
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['point' => $point]);

    $testPlace->point = $point2;

    expect($testPlace->isDirty())->toBeFalse();
});

it('handles casting geometry columns with raw expressions', function (string $expression): void {
    // Arrange
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['point' => DB::raw($expression)]);

    // Act & Assert
    expect(function () use ($testPlace): void {
        // Trigger 'point' attribute to cast raw expression to a `Point` object
        $testPlace->originalIsEquivalent('point');
    })->not->toThrow(Exception::class);
})->with([
    'without SRID' => "ST_GeomFromText('POINT(0 0)')",
    'with SRID' => "ST_GeomFromText('POINT(0 0)', 4326)",
]);

it('handles casting geometry columns with raw expressions with axis order', function (): void {
    // Arrange
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['point' => DB::raw("ST_GeomFromText('POINT(0 0)', 4326, 'axis-order=long-lat')")]);

    // Act & Assert
    expect(function () use ($testPlace): void {
        // Trigger 'point' attribute to cast raw expression to a `Point` object
        $testPlace->originalIsEquivalent('point');
    })->not->toThrow(Exception::class);
})->skip(fn () => ! AxisOrder::supported(DB::connection()));
