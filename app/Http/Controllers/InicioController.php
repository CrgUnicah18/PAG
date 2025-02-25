<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permiso;
use App\Models\Vacacion;
use App\Models\Empleado;
use Carbon\Carbon;

class InicioController extends Controller
{
    /**
     * Mostrar el dashboard con las estadísticas.
     */
    public function index()
    {
        // Fecha de hoy y mañana
        $hoy = Carbon::today();
        $mañana = Carbon::tomorrow();

        // Obtener cumpleaños de hoy y mañana
        $cumpleañosHoy = Empleado::whereMonth('fecha_nacimiento', $hoy->month)
            ->whereDay('fecha_nacimiento', $hoy->day)
            ->get();

        $cumpleañosMañana = Empleado::whereMonth('fecha_nacimiento', $mañana->month)
            ->whereDay('fecha_nacimiento', $mañana->day)
            ->get();

        // Contadores de permisos
        $permisosPendientes = Permiso::where('estado', 'pendiente')->count();
        $permisosAprobados = Permiso::where('estado', 'aprobado')->count();
        $permisosRechazados = Permiso::where('estado', 'rechazado')->count();

        // Contadores de vacaciones
        $vacacionesProximas = Vacacion::where('fecha_inicio', '>=', now())->count();

        // Contadores de empleados
        $totalEmpleados = Empleado::count();
        $empleadosActivos = Empleado::where('estado', 'activo')->count();  // Ajusta este campo a tu necesidad

        return view('admin.inicio.home', compact(
            'permisosPendientes',
            'permisosAprobados',
            'permisosRechazados',
            'vacacionesProximas',
            'totalEmpleados',
            'empleadosActivos',
            'cumpleañosHoy',
            'cumpleañosMañana'
        ));

    }


}
