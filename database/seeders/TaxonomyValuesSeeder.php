<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxonomyValuesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $now = DB::raw("NOW()");

    // brands
    foreach (config('constants.taxonomy.brands') as $brand) {
      DB::table('taxonomy_values')->insert([
        'value' => $brand,
        'value_slug' => strtolower($brand),
        'slug' => 'brand',
        'created_at' => $now,
        'updated_at' => $now
      ]);
    }

    // types
    foreach (config('constants.taxonomy.types') as $name => $slug) {
      DB::table('taxonomy_values')->insert([
        'value_slug' => $slug,
        'value' => $name,
        'slug' => 'type',
        'created_at' => $now,
        'updated_at' => $now
      ]);
    }

    // category
    foreach (config('constants.taxonomy.categories') as $name => $slug) {
      DB::table('taxonomy_values')->insert([
        'value' => $name,
        'value_slug' => $slug,
        'slug' => 'category',
        'created_at' => $now,
        'updated_at' => $now
      ]);
    }

    // product_status
    foreach (config('constants.taxonomy.product_statuses') as $name => $slug) {
      DB::table('taxonomy_values')->insert([
        'value_slug' => $slug,
        'value' => $name,
        'slug' => 'product_status',
        'created_at' => $now,
        'updated_at' => $now
      ]);
    }
  }
}
