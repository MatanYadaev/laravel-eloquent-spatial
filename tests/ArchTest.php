<?php

arch('only the required illuminate packages are used in src')
    ->expect('MatanYadaev\EloquentSpatial')
    ->not->toUse('Illuminate')
    ->ignoring([
        'Illuminate\Database',
        'Illuminate\Support',
        'Illuminate\Contracts',
        'MatanYadaev\EloquentSpatial\EloquentSpatialServiceProvider',
    ]);

arch('container-bound global helpers are not used in src')
    ->expect(['app', 'resolve', 'config', 'event', 'dispatch', 'cache', 'logger', 'report', 'abort', 'now'])
    ->not->toBeUsed();
