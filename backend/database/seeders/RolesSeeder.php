<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $now = DB::raw("NOW()");

    foreach (config('constants.roles') as $name => $priority) {
      DB::table('roles')->insert([
        'name' => $name,
        'priority' => $priority,
        'created_at' => $now,
        'updated_at' => $now
      ]);
    }
  }
}
