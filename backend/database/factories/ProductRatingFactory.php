<?php

namespace Database\Factories;

use App\Models\Product\ProductRating;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductRatingFactory extends Factory
{
  protected $model = ProductRating::class;

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'user_id' => User::all()->random(1)->first()->id,
      'value' => rand(1, 5)
    ];
  }
}
