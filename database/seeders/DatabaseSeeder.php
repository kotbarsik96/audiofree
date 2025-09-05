<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\TaxonomiesSeeder;
use Database\Seeders\TaxonomyValuesSeeder;
use Database\Seeders\RolesSeeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call([
      SeoSeeder::class,
      RolesSeeder::class,
      UserSeeder::class,
      AttachmentSeeder::class,
      TaxonomiesSeeder::class,
      TaxonomyValuesSeeder::class,
      ProductSeeder::class,
      ProductInfoValuesSeeder::class,
    ]);
  }
}
