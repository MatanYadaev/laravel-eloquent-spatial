<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use MatanYadaev\EloquentSpatial\Tests\TestCase;

uses(TestCase::class, DatabaseTransactions::class)
    ->in('Objects', 'GeometryCastTest.php', 'HasSpatialTest.php', 'DoctrineTypesTest.php');
