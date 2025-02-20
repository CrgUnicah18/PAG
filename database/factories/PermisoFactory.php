<?php

namespace Database\Factories;

use App\Models\Permiso;
use App\Models\Empleado;
use App\Models\TipoPermiso;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermisoFactory extends Factory
{
    protected $model = Permiso::class;

    public function definition()
    {
        return [
            'fecha_inicio' => $this->faker->date(),
            'fecha_fin' => $this->faker->date(),
            'empleado_id' => Empleado::inRandomOrder()->first()->id, // Obtiene un empleado aleatorio
            'tipo_permiso_id' => TipoPermiso::inRandomOrder()->first()->id, // Obtiene un tipo de permiso aleatorio
        ];
    }
}
