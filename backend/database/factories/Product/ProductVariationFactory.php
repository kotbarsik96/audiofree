<?php

namespace Database\Factories\Product;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchid\Attachment\Models\Attachment;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductVariationFactory extends Factory
{
  public static $names = [
    'Чёрный',
    'Белый',
    'Красный',
    'Синий',
    'Голубой',
    'Зелёный',
    'Пурпурный',
  ];

  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $images = Attachment::where('group', config('constants.product.image_group'))
      ->get()->pluck('id');

    return [
      'price' => rand(1000, 99999),
      'discount' => rand(0, 75),
      'quantity' => rand(1, 100),
      'image_id' => fake()->randomElement($images),
      'name' => fake()->randomElement(self::$names),
      'created_by' => 1,
      'updated_by' => 1,
    ];
  }
}
