<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run()
    {
        // Crear roles

        Role::create(['name' => 'supervisor']);
        Role::create(['name' => 'empleado']);
    }
}
