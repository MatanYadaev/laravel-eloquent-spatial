<?php

namespace MatanYadaev\EloquentSpatial\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use MatanYadaev\EloquentSpatial\EloquentSpatialServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // @phpstan-ignore-next-line
        if (version_compare(Application::VERSION, '11.0.0', '>=')) {
            $this->loadMigrationsFrom(__DIR__.'/database/migrations-laravel->=11');
        } else {
            $this->loadMigrationsFrom(__DIR__.'/database/migrations-laravel-<=10');
        }
    }

    /**
     * @return class-string<ServiceProvider>[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            EloquentSpatialServiceProvider::class,
        ];
    }
}
