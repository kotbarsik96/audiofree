<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use Database\Seeders\RolesSeeder;
use Database\Seeders\TaxonomiesTypesSeeder;
use Database\Seeders\TaxonomiesSeeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call([
      // RolesSeeder::class,
      TaxonomiesTypesSeeder::class,
      TaxonomiesSeeder::class,
    ]);
  }
}
