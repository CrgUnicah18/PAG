<?php

namespace Database\Factories;

use App\Models\Oficina;
use Illuminate\Database\Eloquent\Factories\Factory;

class OficinaFactory extends Factory
{
    protected $model = Oficina::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->city(),
            'direccion' => $this->faker->address(),
        ];
    }
}
