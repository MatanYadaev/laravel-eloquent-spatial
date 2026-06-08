<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\Facade;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\Standalone\StandalonePlace;

function standaloneConnection(): Connection
{
    $driver = (getenv('DB_CONNECTION') ?: 'mysql') === 'pgsql' ? 'pgsql' : 'mysql';

    $capsule = new Capsule;
    $capsule->addConnection([
        'driver' => $driver,
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: ($driver === 'pgsql' ? '5432' : '3306'),
        'database' => getenv('DB_DATABASE') ?: 'laravel_eloquent_spatial_test',
        'username' => getenv('DB_USERNAME') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
    ]);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule->getConnection();
}

function createStandalonePlace(): void
{
    $place = new StandalonePlace;
    $place->location = new Point(0, 0, 4326);
    $place->save();
}

beforeEach(function (): void {
    Facade::clearResolvedInstances();
    Facade::setFacadeApplication(null);

    $connection = standaloneConnection();

    $connection->getSchemaBuilder()->dropIfExists('standalone_places');
    $connection->getSchemaBuilder()->create('standalone_places', function ($table): void {
        $table->id();
    });

    if ($connection->getDriverName() === 'pgsql') {
        $connection->statement('ALTER TABLE standalone_places ADD COLUMN location geometry(Point, 4326)');
    } else {
        $connection->statement('ALTER TABLE standalone_places ADD COLUMN location POINT');
    }
});

afterEach(function (): void {
    Capsule::connection()->getSchemaBuilder()->dropIfExists('standalone_places');
});

it('persists and reads a geometry cast without a facade root', function (): void {
    // Arrange
    createStandalonePlace();

    // Act
    /** @var StandalonePlace $fetched */
    $fetched = StandalonePlace::query()->firstOrFail();

    // Assert
    expect($fetched->location)->toBeInstanceOf(Point::class)
        ->and($fetched->location->latitude)->toBe(0.0)
        ->and($fetched->location->longitude)->toBe(0.0);
});

it('runs spatial scopes without a facade root', function (): void {
    // Arrange
    createStandalonePlace();

    // Act
    /** @var StandalonePlace $fetched */
    $fetched = StandalonePlace::query()
        ->withDistance('location', new Point(1, 1, 4326))
        ->whereDistance('location', new Point(1, 1, 4326), '<', 1e12)
        ->firstOrFail();

    // Assert
    expect($fetched->distance)->toBeNumeric()
        ->and((float) $fetched->distance)->toBeGreaterThan(0.0);
});
