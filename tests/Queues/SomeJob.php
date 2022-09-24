<?php

namespace MatanYadaev\EloquentSpatial\Tests\Queues;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class SomeJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $testPlace;

    public function __construct(TestPlace $testPlace)
    {
        $this->testPlace = $testPlace;
    }

    public function handle()
    {
        // code...
    }
}
