<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxonomiesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $now = DB::raw("NOW()");

    DB::table('taxonomies')->insert([
      'name' => 'Бренд',
      'slug' => 'brand',
      'group' => 'products',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomies')->insert([
      'name' => 'Категория',
      'slug' => 'category',
      'group' => 'products',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomies')->insert([
      'name' => 'Тип',
      'slug' => 'type',
      'group' => 'products',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomies')->insert([
      'name' => 'Статус товара',
      'slug' => 'product_status',
      'group' => 'products',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomies')->insert([
      'name' => 'Статус заказа',
      'slug' => 'order_status',
      'group' => 'products',
      'created_at' => $now,
      'updated_at' => $now
    ]);
  }
}
