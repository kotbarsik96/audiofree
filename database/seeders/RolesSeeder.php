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

    DB::table('roles')->insert([
      'slug' => 'developer',
      'name' => 'Разработчик',
      'permissions' => '{"platform.index": "1", "platform.systems.roles": "1", "platform.systems.users": "1", "platform.systems.products": "1", "platform.systems.attachment": "1", "platform.systems.seo": "1", "platform.systems.support": "1"}',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('roles')->insert([
      'slug' => 'administrator',
      'name' => 'Администратор',
      'permissions' => '{"platform.index": "1", "platform.systems.roles": "0", "platform.systems.users": "1", "platform.systems.products": "1", "platform.systems.attachment": "1", "platform.systems.seo": "1"}',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('roles')->insert([
      'slug' => 'manager',
      'name' => 'Менеджер',
      'permissions' => '{"platform.index": "1", "platform.systems.roles": "0", "platform.systems.users": "0", "platform.systems.products": "1", "platform.systems.attachment": "1"}',
      'created_at' => $now,
      'updated_at' => $now
    ]);
    DB::table('roles')->insert([
      'slug' => 'support',
      'name' => 'Сотрудник тех.поддержки',
      'permissions' => '{"platform.index": "1", "platform.systems.roles": "0", "platform.systems.users": "0", "platform.systems.products": "0", "platform.platform.systems.support: "1", systems.attachment": "1"}',
      'created_at' => $now,
      'updated_at' => $now
    ]);
  }
}
