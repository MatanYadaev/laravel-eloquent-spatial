<?php

namespace MatanYadaev\EloquentSpatial\Tests\TestModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Builders\SpatialBuilder;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestFactories\TestSridPlaceFactory;

class TestSridPlace extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'location',
    ];

    protected $casts = [
        'location' => Point::class,
    ];

    public static function query(): SpatialBuilder
    {
        return parent::query();
    }

    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }

    protected static function newFactory(): TestSridPlaceFactory
    {
        return new TestSridPlaceFactory();
    }
}
