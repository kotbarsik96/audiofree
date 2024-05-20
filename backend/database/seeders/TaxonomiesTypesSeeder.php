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
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomies_types')->insert([
      'type' => 'category',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomies_types')->insert([
      'type' => 'type',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('taxonomies_types')->insert([
      'type' => 'product_status',
      'created_at' => $now,
      'updated_at' => $now
    ]);
  }
}
