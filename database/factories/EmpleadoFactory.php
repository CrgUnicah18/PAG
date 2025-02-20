<?php

namespace Database\Factories;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpleadoFactory extends Factory
{
    protected $model = Empleado::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->name(),
            'apellido' => fake()->lastName(), // Genera un apellido aleatorio
            'direccion' => $this->faker->address(),
            'telefono' => $this->faker->phoneNumber(),
            'fecha_nacimiento' => $this->faker->date(),
            'oficina_id' => \App\Models\Oficina::factory(),
            'grupo_id' => \App\Models\Grupo::factory(),
        ];
    }
}
