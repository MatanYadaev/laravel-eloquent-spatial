<?php

namespace MatanYadaev\EloquentSpatial\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
  protected function setUp(): void
  {
    parent::setUp();

    $this->loadMigrationsFrom(__DIR__.'/database/migrations');
  }

  public function getEnvironmentSetUp($app): void
  {
    Config::set('database.default', 'mysql');
    Config::set('database.connections.mysql', [
      'driver' => 'mysql',
      'host' => '127.0.0.1',
      'port' => env('DB_PORT', 3306),
      'database' => 'laravel_eloquent_spatial_test',
      'username' => 'root',
    ]);
  }
}
