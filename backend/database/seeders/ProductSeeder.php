<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Product\ProductInfo;
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
    Product::factory()
      ->count($productCount)
      ->has(ProductInfo::factory()->count(3), 'info')
      ->create();
  }
}
