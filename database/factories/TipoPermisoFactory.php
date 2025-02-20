<?php

namespace Database\Factories;

use App\Models\TipoPermiso;
use Illuminate\Database\Eloquent\Factories\Factory;

class TipoPermisoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TipoPermiso::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->word,  // Genera un nombre aleatorio
            'descripcion' => $this->faker->sentence,  // Genera una descripción aleatoria
        ];
    }
}
