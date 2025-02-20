<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Empleado;
use App\Models\Oficina;
use App\Models\Grupo;
use App\Models\Permiso;
use App\Models\Vacacion;
use App\Models\TipoPermiso;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // Crear tipos de permisos de ejemplo usando el factory
        TipoPermiso::factory(3)->create(); // Crear 3 tipos de permisos

        // Crear oficinas de ejemplo
        Oficina::factory(10)->create(); // 10 oficinas

        // Crear grupos de ejemplo
        Grupo::factory(5)->create(); // 5 grupos

        // Crear empleados de ejemplo
        Empleado::factory(20)->create(); // 20 empleados

        // Crear permisos de ejemplo
        Permiso::factory(30)->create()->each(function ($permiso) {
            $empleado = Empleado::inRandomOrder()->first(); // Obtener un empleado aleatorio
            $tipoPermiso = TipoPermiso::inRandomOrder()->first(); // Obtener un tipo de permiso aleatorio

            // Asignar el empleado al permiso
            $permiso->empleado()->associate($empleado);

            // Asignar tipo_permiso_id correctamente
            $permiso->tipo_permiso_id = $tipoPermiso->id;

            // Guardar el permiso
            $permiso->save();
        });
        // Crear vacaciones de ejemplo
        Vacacion::factory(15)->create()->each(function ($vacacion) {
            $empleado = Empleado::inRandomOrder()->first(); // Obtener un empleado aleatorio
            $vacacion->empleado()->associate($empleado); // Asignar el empleado a la vacación
            $vacacion->save();
        });

        // Crear usuarios con empleados relacionados
        User::factory(20)->create(); // 20 usuarios de ejemplo
    }
}
