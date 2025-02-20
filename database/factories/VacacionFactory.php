<?php

namespace Database\Factories;

use App\Models\Vacacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class VacacionFactory extends Factory
{
    protected $model = Vacacion::class;

    public function definition()
    {
        return [
            'fecha_inicio' => $this->faker->date(),
            'fecha_fin' => $this->faker->date(),
            'empleado_id' => \App\Models\Empleado::factory(),
        ];
    }
}

