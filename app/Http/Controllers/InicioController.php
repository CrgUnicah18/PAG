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

        // Inicializamos contadores
        $permisosPendientes = 0;
        $permisosAprobados = 0;
        $permisosRechazados = 0;
        $permisosPendienteAprobacion = 0; // Contador para el nuevo estado
        $vacacionesProximas = 0;

        // Datos específicos para el empleado logueado
        if (auth()->user()->hasRole('empleado')) {
            // Obtener el empleado logueado
            $empleado = auth()->user()->empleado;

            // Contadores de permisos solo para el empleado logueado
            $permisosPendientes = Permiso::where('empleado_id', $empleado->id)
                ->where('estado', 'pendiente')
                ->count();
            $permisosAprobados = Permiso::where('empleado_id', $empleado->id)
                ->where('estado', 'aprobado')
                ->count();
            $permisosRechazados = Permiso::where('empleado_id', $empleado->id)
                ->where('estado', 'rechazado')
                ->count();
            $permisosPendienteAprobacion = Permiso::where('empleado_id', $empleado->id)
                ->where('estado', 'pendiente_aprobacion')  // Contar permisos en el estado pendiente_aprobacion
                ->count();

            // Contadores de vacaciones solo para el empleado logueado
            $vacacionesProximas = Vacacion::where('empleado_id', $empleado->id)
                ->where('fecha_inicio', '>=', now())
                ->count();

            return view('empleado.inicio.home', compact(
                'permisosPendientes',
                'permisosAprobados',
                'permisosRechazados',
                'permisosPendienteAprobacion',
                'vacacionesProximas',
                'cumpleañosHoy',
                'cumpleañosMañana'
            ));
        }

        // Inicializar variables
        $empleadosAsignados = 0;
        $empleadosActivos = 0;
        $totalEmpleados = 0;

        if (auth()->user()->hasRole('supervisor')) {
            // Obtener empleados asignados al supervisor
            $empleadosAsignados = Empleado::where('supervisor_id', auth()->user()->empleado->id)->get();
            $empleadosActivos = $empleadosAsignados->where('estado', 'activo')->count();
            $totalEmpleados = $empleadosAsignados->count();

            $supervisor = auth()->user()->empleado;

            // Obtener permisos del propio supervisor
            $permisosSupervisor = Permiso::where('empleado_id', $supervisor->id)->get();

            // Contadores de permisos filtrados por los empleados asignados
            $empleadosIds = $empleadosAsignados->pluck('id')->toArray(); // Convertimos la colección de IDs a un array
            $permisosPendientes = Permiso::whereIn('empleado_id', $empleadosIds)
                ->where('estado', 'pendiente')
                ->count();
            $permisosAprobados = Permiso::whereIn('empleado_id', $empleadosIds)
                ->where('estado', 'aprobado')
                ->count();
            $permisosRechazados = Permiso::whereIn('empleado_id', $empleadosIds)
                ->where('estado', 'rechazado')
                ->count();
            $permisosPendienteAprobacion = Permiso::whereIn('empleado_id', $empleadosIds)
                ->where('estado', 'pendiente_aprobacion')
                ->count();

            // Contadores de vacaciones filtrados por los empleados asignados
            $vacacionesProximas = Vacacion::whereIn('empleado_id', $empleadosIds)
                ->where('fecha_inicio', '>=', now())
                ->count();

            // Retornar vista con los datos
            return view('supervisor.inicio.home', compact(
                'permisosPendientes',
                'permisosAprobados',
                'permisosRechazados',
                'permisosPendienteAprobacion',
                'vacacionesProximas',
                'totalEmpleados',
                'empleadosActivos',
                'empleadosAsignados',
                'cumpleañosHoy',
                'cumpleañosMañana',
                'permisosSupervisor'
            ));
        } else {
            // Si es admin, mostrar todos los empleados y contar permisos/vacaciones sin filtro
            $totalEmpleados = Empleado::count();
            $empleadosActivos = Empleado::where('estado', 'activo')->count();

            // Contadores de permisos y vacaciones sin filtrar por supervisor
            $permisosPendientes = Permiso::where('estado', 'pendiente')->count();
            $permisosAprobados = Permiso::where('estado', 'aprobado')->count();
            $permisosRechazados = Permiso::where('estado', 'rechazado')->count();
            $permisosPendienteAprobacion = Permiso::where('estado', 'pendiente_aprobacion')->count();
            $vacacionesProximas = Vacacion::where('fecha_inicio', '>=', now())->count();
            // Retornar vista con los datos correspondientes
            return view('admin.inicio.home', compact(
                'permisosPendientes',
                'permisosAprobados',
                'permisosRechazados',
                'permisosPendienteAprobacion', // Pasamos la variable al view
                'vacacionesProximas',
                'totalEmpleados',
                'empleadosActivos',
                'cumpleañosHoy',
                'cumpleañosMañana',
                'empleadosAsignados'
            ));

        }
    }




    /*    public function index()
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

   }.
   
   */


}
