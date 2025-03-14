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
            $empleado->vacaciones_restantes = $empleado->calcularBalanceVacaciones();
            $empleado->save();
        }

        $this->info('Vacaciones restantes actualizadas para todos los empleados.');
    }
}