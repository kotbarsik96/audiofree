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
      'name' => 'brand',
      'group' => 'products',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomies')->insert([
      'name' => 'category',
      'group' => 'products',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomies')->insert([
      'name' => 'type',
      'group' => 'products',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomies')->insert([
      'name' => 'product_status',
      'group' => 'products',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomies')->insert([
      'name' => 'order_status',
      'group' => 'products',
      'created_at' => $now,
      'updated_at' => $now
    ]);
  }
}
