<?php

namespace MatanYadaev\EloquentSpatial\Tests\Queues;

use Illuminate\Queue\SerializesModels;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class Invoice
{
    use SerializesModels;

    public $testPlace;

    public function __construct(TestPlace $testPlace)
    {
        $this->testPlace = $testPlace;
    }

    /**
     * Random domain specific method(s) to make the invoice for the user.
     */
    public function buildTheInvoice()
    {
        // ...
    }
}
