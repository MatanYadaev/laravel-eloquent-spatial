<?php

namespace MatanYadaev\EloquentSpatial\Tests;

use Illuminate\Support\ServiceProvider;
use MatanYadaev\EloquentSpatial\EloquentSpatialServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
  protected function setUp(): void
  {
    parent::setUp();

    $this->loadMigrationsFrom(__DIR__.'/database/migrations');
  }

  /**
   * @param $app
   * @return class-string<ServiceProvider>[]
   */
  protected function getPackageProviders($app): array
  {
    return [
      EloquentSpatialServiceProvider::class,
    ];
  }
}
