<?php

namespace Database\Factories;

use App\Models\Vacacion;
use App\Models\Empleado;
use App\Models\TipoPermiso;
use Illuminate\Database\Eloquent\Factories\Factory;

class VacacionFactory extends Factory
{
    protected $model = Vacacion::class;

    public function definition()
    {
        // Generamos una fecha de inicio aleatoria
        $fecha_inicio = $this->faker->date();

        // Generamos una fecha de fin posterior a la fecha de inicio
        $fecha_fin = $this->faker->dateTimeBetween($fecha_inicio, '+1 year'); // Hasta 1 año después

        return [
            'fecha_inicio' => $this->faker->date(),
            'fecha_fin' => $this->faker->dateTimeBetween('+1 day', '+1 year'),
            'empleado_id' => Empleado::factory(),
            'estado' => $this->faker->randomElement(['pendiente', 'aprobada', 'rechazada']),
            'tipo_permiso_id' => \App\Models\TipoPermiso::inRandomOrder()->first()->id ?? \App\Models\TipoPermiso::factory(),
        ];
    }
}
