<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empleado;

class ActualizarVacacionesAnuales extends Command
{
    protected $signature = 'empleados:actualizar-vacaciones-anuales';
    protected $description = 'Actualizar el campo vacaciones_restantes para todos los empleados anualmente';

    public function handle()
    {
        $empleados = Empleado::all();

        foreach ($empleados as $empleado) {
            // Llamamos al método para calcular las vacaciones
            $resultadoVacaciones = $empleado->calcularBalanceVacaciones();

            // Asignamos solo el valor de 'vacaciones_restantes' al empleado
            $empleado->vacaciones_restantes = $resultadoVacaciones['vacaciones_restantes'];

            // Guardamos el empleado con el nuevo valor
            $empleado->save();
        }

        $this->info('Vacaciones restantes actualizadas para todos los empleados.');
    }
}
