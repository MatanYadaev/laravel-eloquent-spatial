<?php

namespace MatanYadaev\EloquentSpatial\Tests\TestFactories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

class TestPlaceFactory extends Factory
{
    protected $model = TestPlace::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->streetName,
            'address' => $this->faker->address,
            'location' => new Point($this->faker->latitude, $this->faker->longitude),
        ];
    }
}
