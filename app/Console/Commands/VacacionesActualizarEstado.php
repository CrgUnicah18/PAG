<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vacacion;
use App\Models\Empleado;
use Carbon\Carbon;

class VacacionesActualizarEstado extends Command
{
    protected $signature = 'vacaciones:actualizar-estado';
    protected $description = 'Actualizar el estado de los empleados cuando terminan sus vacaciones';

    public function handle()
    {
        $hoy = Carbon::now()->toDateString();

        // Traer todas las vacaciones que ya finalizaron hoy o antes
        $vacacionesTerminadas = Vacacion::whereDate('fecha_fin', '<=', $hoy)->get();

        foreach ($vacacionesTerminadas as $vacacion) {
            $empleado = Empleado::find($vacacion->empleado_id);

            // Solo actualizar si aún no está activo
            if ($empleado && $empleado->estado !== 'activo') {
                $empleado->estado = 'activo';
                $empleado->save();
            }
        }

        $this->info('Estados de empleados actualizados desde vacaciones finalizadas.');
    }
}
