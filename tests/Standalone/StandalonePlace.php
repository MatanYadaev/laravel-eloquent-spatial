<?php

declare(strict_types=1);

namespace MatanYadaev\EloquentSpatial\Tests\Standalone;

use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

/**
 * @property Point $location
 * @property float|null $distance
 *
 * @mixin Model
 */
class StandalonePlace extends Model
{
    use HasSpatial;

    protected $table = 'standalone_places';

    public $timestamps = false;

    protected $guarded = [];

    /** @var array<string, string> */
    protected $casts = ['location' => Point::class];
}
