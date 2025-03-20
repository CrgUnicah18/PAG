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
        $empleadosConPermiso = Empleado::where('estado', 'inactivo')->get();

        foreach ($empleadosConPermiso as $empleado) {
            // Obtener los permisos aprobados del empleado
            $permisosAprobados = $empleado->permisos()
                ->where('estado', 'aprobado')
                ->get();

            // Si TODOS los permisos aprobados ya terminaron, lo volvemos activo
            $todosFinalizados = $permisosAprobados->every(function ($permiso) {
                return $permiso->fecha_fin < now();
            });

            if ($todosFinalizados) {
                $empleado->estado = 'activo';
                $empleado->save();
            }
        }

        $this->info('Estado de los empleados actualizado correctamente.');
    }

}
