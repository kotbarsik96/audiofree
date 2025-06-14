<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Product\ProductInfo;
use App\Models\Product\ProductRating;
use App\Models\Product\ProductVariation;
use Database\Factories\Product\ProductFactory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $productCount = count(ProductFactory::$productNames);

    for ($i = 0; $i < $productCount; $i++) {
      Product::factory()
        ->has(ProductInfo::factory()->count(rand(15, 20)), 'info')
        ->has(ProductVariation::factory()->count(rand(2, 4)), 'variations')
        ->has(ProductRating::factory()->count(rand(1, 20)), 'rating')
        ->createQuietly();
    }
  }
}
