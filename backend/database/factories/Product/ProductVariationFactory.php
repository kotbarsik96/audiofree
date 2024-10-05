<?php

namespace Database\Factories\Product;

use App\Models\Product\ProductVariation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchid\Attachment\Models\Attachment;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductVariationFactory extends Factory
{
  protected $galleryImagesIds = [];

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

  public function attachGalleries(ProductVariation $variation)
  {
    $this->galleryImagesIds = Attachment::where('group', config('constants.product.variation.gallery_group'))
      ->get()->pluck('id');

    $count = fake()->numberBetween(1, config('constants.product.variation.max_gallery_images'));

    for ($i = 0; $i < $count; $i++) {
      DB::table('attachmentable')->insert([
        'attachmentable_type' => ProductVariation::class,
        'attachmentable_id' => $variation->id,
        'attachment_id' => fake()->randomElement($this->galleryImagesIds)
      ]);
    }
  }

  public function configure()
  {
    return $this->afterCreating(function (ProductVariation $variation) {
      $this->attachGalleries($variation);
    });
  }
}
