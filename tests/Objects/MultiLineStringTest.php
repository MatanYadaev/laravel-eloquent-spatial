<?php

use MatanYadaev\EloquentSpatial\EloquentSpatial;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Geometry;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use MatanYadaev\EloquentSpatial\Objects\MultiLineString;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestExtendedPlace;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;
use MatanYadaev\EloquentSpatial\Tests\TestObjects\ExtendedMultiLineString;

it('creates a model record with multi line string', function (): void {
    // Arrange
    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ]);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['multi_line_string' => $multiLineString]);

    // Assert
    expect($testPlace->multi_line_string)->toBeInstanceOf(MultiLineString::class);
    expect($testPlace->multi_line_string)->toEqual($multiLineString);
});

it('creates a model record with multi line string with SRID integer', function (): void {
    // Arrange
    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ], Srid::WGS84->value);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['multi_line_string' => $multiLineString]);

    // Assert
    expect($testPlace->multi_line_string->srid)->toBe(Srid::WGS84->value);
});

it('creates a model record with multi line string with SRID enum', function (): void {
    // Arrange
    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ], Srid::WGS84);

    // Act
    /** @var TestPlace $testPlace */
    $testPlace = TestPlace::factory()->create(['multi_line_string' => $multiLineString]);

    // Assert
    expect($testPlace->multi_line_string->srid)->toBe(Srid::WGS84->value);
});

it('creates multi line string from JSON', function (): void {
    // Arrange
    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ]);

    // Act
    $multiLineStringFromJson = MultiLineString::fromJson('{"type":"MultiLineString","coordinates":[[[180,0],[179,1]]]}');

    // Assert
    expect($multiLineStringFromJson)->toEqual($multiLineString);
});

it('creates multi line string with SRID from JSON', function (): void {
    // Arrange
    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ], Srid::WGS84->value);

    // Act
    $multiLineStringFromJson = MultiLineString::fromJson('{"type":"MultiLineString","coordinates":[[[180,0],[179,1]]]}', Srid::WGS84->value);

    // Assert
    expect($multiLineStringFromJson)->toEqual($multiLineString);
});

it('creates multi line string from array', function (): void {
    // Arrange
    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ]);

    // Act
    $multiLineStringFromJson = MultiLineString::fromArray(['type' => 'MultiLineString', 'coordinates' => [[[180, 0], [179, 1]]]]);

    // Assert
    expect($multiLineStringFromJson)->toEqual($multiLineString);
});

it('creates multi line string with SRID from array', function (): void {
    // Arrange
    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ], Srid::WGS84->value);

    // Act
    $multiLineStringFromJson = MultiLineString::fromArray(['type' => 'MultiLineString', 'coordinates' => [[[180, 0], [179, 1]]]], Srid::WGS84->value);

    // Assert
    expect($multiLineStringFromJson)->toEqual($multiLineString);
});

it('generates multi line string JSON', function (): void {
    // Arrange
    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ]);

    // Act
    $json = $multiLineString->toJson();

    // Assert
    $expectedJson = '{"type":"MultiLineString","coordinates":[[[180,0],[179,1]]]}';
    expect($json)->toBe($expectedJson);
});

it('generates multi line string feature collection JSON', function (): void {
    // Arrange
    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ]);

    // Act
    $featureCollectionJson = $multiLineString->toFeatureCollectionJson();

    // Assert
    $expectedFeatureCollectionJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":[],"geometry":{"type":"MultiLineString","coordinates":[[[180,0],[179,1]]]}}]}';
    expect($featureCollectionJson)->toBe($expectedFeatureCollectionJson);
});

it('creates multi line string from WKT', function (): void {
    // Arrange
    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ]);

    // Act
    $multiLineStringFromWkt = MultiLineString::fromWkt('MULTILINESTRING((180 0, 179 1))');

    // Assert
    expect($multiLineStringFromWkt)->toEqual($multiLineString);
});

it('creates multi line string with SRID from WKT', function (): void {
    // Arrange
    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ], Srid::WGS84->value);

    // Act
    $multiLineStringFromWkt = MultiLineString::fromWkt('MULTILINESTRING((180 0, 179 1))', Srid::WGS84->value);

    // Assert
    expect($multiLineStringFromWkt)->toEqual($multiLineString);
});

it('creates multi line string from EWKT', function (): void {
    // Arrange
    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ], Srid::WGS84->value);

    // Act
    $multiLineStringFromEwkt = MultiLineString::fromWkt('SRID=4326;MULTILINESTRING((180 0, 179 1))');

    // Assert
    expect($multiLineStringFromEwkt)->toEqual($multiLineString);
    expect($multiLineStringFromEwkt->srid)->toBe(Srid::WGS84->value);
});

it('generates multi line string WKT', function (): void {
    // Arrange
    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ]);

    // Act
    $wkt = $multiLineString->toWkt();

    // Assert
    $expectedWkt = 'MULTILINESTRING((180 0, 179 1))';
    expect($wkt)->toBe($expectedWkt);
});

it('creates multi line string from WKB', function (): void {
    // Arrange
    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ]);

    // Act
    $multiLineStringFromWkb = MultiLineString::fromWkb($multiLineString->toWkb());

    // Assert
    expect($multiLineStringFromWkb)->toEqual($multiLineString);
});

it('creates multi line string with SRID from WKB', function (): void {
    // Arrange
    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ], Srid::WGS84->value);

    // Act
    $multiLineStringFromWkb = MultiLineString::fromWkb($multiLineString->toWkb());

    // Assert
    expect($multiLineStringFromWkb)->toEqual($multiLineString);
});

it('throws exception when multi line string has no line strings', function (): void {
    // Act
    $act = static fn () => new MultiLineString([]);

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('throws exception when creating multi line string from incorrect geometry', function (): void {
    // Act
    // @phpstan-ignore-next-line
    $act = static fn () => new MultiLineString([
        new Point(0, 0),
    ]);

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('casts a MultiLineString to a string', function (): void {
    // Arrange
    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ]);

    // Act
    $string = $multiLineString->__toString();

    // Assert
    expect($string)->toEqual('MULTILINESTRING((180 0, 179 1))');
});

it('adds a macro toMultiLineString', function (): void {
    // Arrange
    Geometry::macro('getName', function (): string {
        /** @var Geometry $this */
        return class_basename($this);
    });

    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ]);

    // Act
    // @phpstan-ignore-next-line
    $name = $multiLineString->getName();

    // Assert
    // @phpstan-ignore-next-line
    expect($name)->toBe('MultiLineString');
});

it('uses an extended MultiLineString class', function (): void {
    // Arrange
    EloquentSpatial::useMultiLineString(ExtendedMultiLineString::class);
    $multiLineString = new ExtendedMultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ], 4326);

    // Act
    /** @var TestExtendedPlace $testPlace */
    $testPlace = TestExtendedPlace::factory()->create(['multi_line_string' => $multiLineString])->fresh();

    // Assert
    expect($testPlace->multi_line_string)->toBeInstanceOf(ExtendedMultiLineString::class);
    expect($testPlace->multi_line_string)->toEqual($multiLineString);
});

it('throws exception when storing a record with regular MultiLineString instead of the extended one', function (): void {
    // Arrange
    EloquentSpatial::useMultiLineString(ExtendedMultiLineString::class);
    $multiLineString = new MultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ], 4326);

    // Act
    $act = static function () use ($multiLineString): void {
        TestExtendedPlace::factory()->create(['multi_line_string' => $multiLineString]);
    };

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});

it('throws exception when storing a record with extended MultiLineString instead of the regular one', function (): void {
    // Arrange
    EloquentSpatial::useMultiLineString(MultiLineString::class);
    $multiLineString = new ExtendedMultiLineString([
        new LineString([
            new Point(0, 180),
            new Point(1, 179),
        ]),
    ], 4326);

    // Act
    $act = static function () use ($multiLineString): void {
        TestPlace::factory()->create(['multi_line_string' => $multiLineString]);
    };

    // Assert
    expect($act)->toThrow(InvalidArgumentException::class);
});
