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
    DB::table('taxonomy_values')->insert([
      'value' => 'Apple',
      'slug' => 'brand',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomy_values')->insert([
      'value' => 'Samsung',
      'slug' => 'brand',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomy_values')->insert([
      'value' => 'Huawei',
      'slug' => 'brand',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomy_values')->insert([
      'value' => 'Xiaomi',
      'slug' => 'brand',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomy_values')->insert([
      'value' => 'JBL',
      'slug' => 'brand',
      'created_at' => $now,
      'updated_at' => $now
    ]);

    // types
    DB::table('taxonomy_values')->insert([
      'value' => 'wired',
      'slug' => 'type',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomy_values')->insert([
      'value' => 'wireless',
      'slug' => 'type',
      'created_at' => $now,
      'updated_at' => $now
    ]);

    // category
    DB::table('taxonomy_values')->insert([
      'value' => 'headphones',
      'slug' => 'category',
      'created_at' => $now,
      'updated_at' => $now
    ]);

    // product_status
    foreach (config('constants.product.statuses') as $name) {
      DB::table('taxonomy_values')->insert([
        'value' => $name,
        'slug' => 'product_status',
        'created_at' => $now,
        'updated_at' => $now
      ]);
    }

    // order_status
    foreach (config('constants.order.statuses') as $name) {
      DB::table('taxonomy_values')->insert([
        'value' => $name,
        'slug' => 'order_status',
        'created_at' => $now,
        'updated_at' => $now
      ]);
    }
  }
}
