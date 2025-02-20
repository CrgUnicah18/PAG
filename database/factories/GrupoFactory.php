<?php

namespace Database\Factories;

use App\Models\Grupo;
use App\Models\Oficina;
use Illuminate\Database\Eloquent\Factories\Factory;

class GrupoFactory extends Factory
{
    protected $model = Grupo::class;

    public function definition(): array
    {
        return [
            'nombre' => $this->faker->word(),
            'oficina_id' => Oficina::inRandomOrder()->first()->id, // Asignamos un oficina_id aleatorio
        ];
    }
}
