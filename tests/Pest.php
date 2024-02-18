<?php

use Illuminate\Database\PostgresConnection;
use Illuminate\Support\Facades\DB;
use MatanYadaev\EloquentSpatial\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

expect()->extend('toBeOnPostgres', function (mixed $value) {
  return $this->when(DB::connection() instanceof PostgresConnection, fn () => $this->toBe($value));
});

expect()->extend('toBeOnMysql', function (mixed $value) {
  return $this->when(! (DB::connection() instanceof PostgresConnection), fn () => $this->toBe($value));
});
