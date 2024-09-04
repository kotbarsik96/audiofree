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
      'taxonomy_name' => 'brand',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomy_values')->insert([
      'value' => 'Samsung',
      'taxonomy_name' => 'brand',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomy_values')->insert([
      'value' => 'Huawei',
      'taxonomy_name' => 'brand',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomy_values')->insert([
      'value' => 'Xiaomi',
      'taxonomy_name' => 'brand',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomy_values')->insert([
      'value' => 'JBL',
      'taxonomy_name' => 'brand',
      'created_at' => $now,
      'updated_at' => $now
    ]);

    // types
    DB::table('taxonomy_values')->insert([
      'value' => 'wired',
      'taxonomy_name' => 'type',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomy_values')->insert([
      'value' => 'wireless',
      'taxonomy_name' => 'type',
      'created_at' => $now,
      'updated_at' => $now
    ]);

    // category
    DB::table('taxonomy_values')->insert([
      'value' => 'headphones',
      'taxonomy_name' => 'category',
      'created_at' => $now,
      'updated_at' => $now
    ]);

    // product_status
    foreach (config('constants.product.statuses') as $name) {
      DB::table('taxonomy_values')->insert([
        'value' => $name,
        'taxonomy_name' => 'product_status',
        'created_at' => $now,
        'updated_at' => $now
      ]);
    }

    // order_status
    foreach (config('constants.order.statuses') as $name) {
      DB::table('taxonomy_values')->insert([
        'value' => $name,
        'taxonomy_name' => 'order_status',
        'created_at' => $now,
        'updated_at' => $now
      ]);
    }
  }
}
