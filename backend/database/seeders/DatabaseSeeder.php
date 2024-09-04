<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use Database\Seeders\RolesSeeder;
use Database\Seeders\TaxonomiesSeeder;
use Database\Seeders\TaxonomyValuesSeeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call([
      // RolesSeeder::class,
      TaxonomiesSeeder::class,
      TaxonomyValuesSeeder::class,
    ]);
  }
}
