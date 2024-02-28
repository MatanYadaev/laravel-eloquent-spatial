<?php

use Illuminate\Database\PostgresConnection;
use Illuminate\Support\Facades\DB;

expect()->extend('toBeOnPostgres', function (mixed $value) {
    return $this->when(DB::connection() instanceof PostgresConnection, fn () => $this->toBe($value));
});

expect()->extend('toBeOnMysql', function (mixed $value) {
    return $this->when(! (DB::connection() instanceof PostgresConnection), fn () => $this->toBe($value));
});

expect()->extend('toBeInstanceOfOnPostgres', function (string $type) {
    return $this->when(DB::connection() instanceof PostgresConnection, fn () => $this->toBeInstanceOf($type));
});

expect()->extend('toBeInstanceOfOnMysql', function (string $type) {
    return $this->when(! (DB::connection() instanceof PostgresConnection), fn () => $this->toBeInstanceOf($type));
});
