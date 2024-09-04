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
      'value_slug' => 'apple',
      'slug' => 'brand',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomy_values')->insert([
      'value' => 'Samsung',
      'value_slug' => 'samsung',
      'slug' => 'brand',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomy_values')->insert([
      'value' => 'Huawei',
      'value_slug' => 'huawei',
      'slug' => 'brand',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomy_values')->insert([
      'value' => 'Xiaomi',
      'value_slug' => 'xiaomi',
      'slug' => 'brand',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomy_values')->insert([
      'value' => 'JBL',
      'value_slug' => 'jbl',
      'slug' => 'brand',
      'created_at' => $now,
      'updated_at' => $now
    ]);

    // types
    DB::table('taxonomy_values')->insert([
      'value_slug' => 'wired',
      'value' => 'Проводные',
      'slug' => 'type',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomy_values')->insert([
      'value_slug' => 'wireless',
      'value' => 'Беспроводные',
      'slug' => 'type',
      'created_at' => $now,
      'updated_at' => $now
    ]);

    // category
    DB::table('taxonomy_values')->insert([
      'value' => 'Наушники',
      'value_slug' => 'headphones',
      'slug' => 'category',
      'created_at' => $now,
      'updated_at' => $now
    ]);

    DB::table('taxonomy_values')->insert([
      'value_slug' => 'active',
      'value' => 'Активен',
      'slug' => 'product_status',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomy_values')->insert([
      'value_slug' => 'inactive',
      'value' => 'Неактивен',
      'slug' => 'product_status',
      'created_at' => $now,
      'updated_at' => $now
    ]);

    // order_status
    foreach (config('constants.order.statuses') as $name => $slug) {
      DB::table('taxonomy_values')->insert([
        'value' => $name,
        'value_slug' => $slug,
        'slug' => 'order_status',
        'created_at' => $now,
        'updated_at' => $now
      ]);
    }
  }
}
