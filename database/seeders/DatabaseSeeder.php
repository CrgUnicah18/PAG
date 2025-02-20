<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Empleado;
use App\Models\Oficina;
use App\Models\Grupo;
use App\Models\Permiso;
use App\Models\Vacacion;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // Crear oficinas de ejemplo
        Oficina::factory(10)->create(); // 10 oficinas

        // Crear grupos de ejemplo
        Grupo::factory(5)->create(); // 5 grupos

        // Crear empleados de ejemplo
        Empleado::factory(20)->create(); // 20 empleados

        // Crear permisos de ejemplo
        Permiso::factory(30)->create(); // 30 permisos

        // Crear vacaciones de ejemplo
        Vacacion::factory(15)->create(); // 15 vacaciones

        // Crear usuarios con empleados relacionados
        User::factory(20)->create(); // 20 usuarios de ejemplo
    }
}
