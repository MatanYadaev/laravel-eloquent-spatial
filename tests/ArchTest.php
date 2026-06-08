<?php

test('facades are not used in src')
    ->expect('Illuminate\Support\Facades')
    ->not->toBeUsed()
    ->ignoring('MatanYadaev\EloquentSpatial\EloquentSpatialServiceProvider');

test('framework classes are not used in src')
    ->expect('Illuminate\Foundation')
    ->not->toBeUsed()
    ->ignoring('MatanYadaev\EloquentSpatial\EloquentSpatialServiceProvider');
