<?php

namespace Database\Factories;

use App\Models\Permiso;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermisoFactory extends Factory
{
    protected $model = Permiso::class;

    public function definition()
    {
        return [
            'tipo_permiso' => $this->faker->word(),
            'fecha_inicio' => $this->faker->date(),
            'fecha_fin' => $this->faker->date(),
            'empleado_id' => \App\Models\Empleado::factory(),
        ];
    }
}
