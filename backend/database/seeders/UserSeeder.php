<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $devAdminMail = 'admin@mail.ru';

    if (env('DEV_MODE', false) && !User::where('email', $devAdminMail)->first()) {
      $developer = User::create([
        'email' => $devAdminMail,
        'password' => '$2y$12$gJNA9RXGzRQaf.Gj2/DFx.pRv.jC2UEBtLCsewVVGuyuFebsX13.m',
        'name' => 'Порфирий',
        'email_verified_at' => now()
      ]);
      RoleUser::create([
        'user_id' => $developer->id,
        'role_id' => Role::where('slug', 'developer')->first()->id
      ]);
    }

    User::factory()->count(100)->create();
  }
}
