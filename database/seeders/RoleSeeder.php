<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'Project Manager']);
        Role::create(['name' => 'PM']);
        Role::create(['name' => 'Project Admin']);
        Role::create(['name' => 'PA']);
        Role::create(['name' => 'BOD']);
        Role::create(['name' => 'BOD1']);
        Role::create(['name' => 'Administator']);
        Role::create(['name' => 'Staff Finance']);
        Role::create(['name' => 'SPV Finance']);
    }
}
