<?php

namespace MatanYadaev\EloquentSpatial\Tests\TestFactories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MatanYadaev\EloquentSpatial\Tests\TestModels\TestPlace;

/**
 * @extends Factory<TestPlace>
 */
class TestPlaceFactory extends Factory
{
  protected $model = TestPlace::class;

  /**
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'name' => $this->faker->streetName(),
      'address' => $this->faker->address(),
    ];
  }
}
