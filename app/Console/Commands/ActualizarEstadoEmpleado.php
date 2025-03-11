<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Empleado;

class ActualizarEstadoEmpleado extends Command
{
    protected $signature = 'empleados:actualizar-estado';
    protected $description = 'Actualizar el estado de los empleados a activo si han finalizado su permiso';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Obtén los empleados con permisos activos y verifica si el permiso ya ha terminado.
        $empleadosConPermiso = Empleado::where('estado', 'inactivo')
            ->whereHas('permisos', function ($query) {
                $query->where('fecha_fin', '<', now());
            })
            ->get();

        // Cambia el estado de los empleados a activo.
        foreach ($empleadosConPermiso as $empleado) {
            $empleado->estado = 'activo';
            $empleado->save();
        }

        $this->info('Estado de los empleados actualizado correctamente.');
    }
}
