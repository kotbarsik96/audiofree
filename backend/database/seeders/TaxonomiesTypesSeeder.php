<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxonomiesTypesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $now = DB::raw("NOW()");

    DB::table('taxonomies_types')->insert([
      'type' => 'brand',
      'group' => 'products',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomies_types')->insert([
      'type' => 'category',
      'group' => 'products',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomies_types')->insert([
      'type' => 'type',
      'group' => 'products',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomies_types')->insert([
      'type' => 'product_status',
      'group' => 'products',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomies_types')->insert([
      'type' => 'order_status',
      'group' => 'products',
      'created_at' => $now,
      'updated_at' => $now
    ]);
  }
}
