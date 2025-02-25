<?php

namespace App\Http\Controllers;

use App\Models\Vacacion;
use App\Models\Empleado;
use App\Models\TipoPermiso;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VacacionController extends Controller
{
    // Muestra la lista de solicitudes de vacaciones
    public function index(Request $request)
    {
        $estado = $request->get('estado');


        // Obtener las solicitudes de vacaciones según el filtro de estado
        $vacaciones = Vacacion::when($estado, function ($query) use ($estado) {
            return $query->where('estado', $estado);
        })
            ->whereHas('tipoPermiso', function ($query) {
                // Filtrar solo los tipos de permiso que son vacaciones
                $query->where('es_vacacion', true);
            })
            ->get();

        // Calcular la duración de las vacaciones
        foreach ($vacaciones as $vacacion) {
            $vacacion->duracion_dias = Carbon::parse($vacacion->fecha_inicio)->diffInDays(Carbon::parse($vacacion->fecha_fin)) + 1;
        }

        return view('admin.vacaciones.index', compact('vacaciones'));
    }

    // Muestra el formulario para crear una nueva solicitud de vacaciones
    public function create()
    {
        // Obtener los empleados disponibles
        $empleados = Empleado::all();

        // Obtener solo los tipos de permisos que son vacaciones
        $tiposPermiso = TipoPermiso::where('es_vacacion', true)->get();

        return view('admin.vacaciones.create', compact('empleados', 'tiposPermiso'));
    }

    // Método para almacenar la solicitud de vacaciones
    public function store(Request $request)
    {
        // Validaciones de entrada
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'tipo_permiso_id' => 'required|exists:tipo_permisos,id',
        ]);

        // Calcular duración de días
        $fecha_inicio = Carbon::parse($request->fecha_inicio);
        $fecha_fin = Carbon::parse($request->fecha_fin);
        $duracion_dias = $fecha_inicio->diffInDays($fecha_fin) + 1; // Sumar 1 para incluir ambos días

        // El estado será aprobado cuando se haga desde la interfaz de admin
        $estado = 'aprobado';

        // Crear nueva solicitud de vacaciones
        Vacacion::create([
            'empleado_id' => $request->empleado_id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'duracion_dias' => $duracion_dias, // Guardar duración en la base de datos
            'estado' => $estado, // Estado aprobado directamente
            'tipo_permiso_id' => $request->tipo_permiso_id,
        ]);

        return redirect()->route('admin.vacaciones.index')->with('success', 'Vacaciones asignadas correctamente.');
    }

    // Aprueba una solicitud de vacaciones
    public function aprobar($id)
    {
        $vacacion = Vacacion::findOrFail($id);
        $vacacion->estado = 'aprobado';
        $vacacion->save();

        return redirect()->route('admin.vacaciones.index')->with('success', 'Vacaciones aprobadas exitosamente.');
    }

    // Declina una solicitud de vacaciones
    public function declinar($id)
    {
        $vacacion = Vacacion::findOrFail($id);
        $vacacion->estado = 'rechazado';
        $vacacion->save();

        return redirect()->route('admin.vacaciones.index')->with('success', 'Vacaciones rechazadas.');
    }

    // Método para actualizar las fechas de la solicitud de vacaciones
    public function update(Request $request, $id)
    {
        $vacacion = Vacacion::findOrFail($id);

        // Validaciones de entrada para las fechas
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        // Calcular duración de días
        $fecha_inicio = Carbon::parse($request->fecha_inicio);
        $fecha_fin = Carbon::parse($request->fecha_fin);
        $duracion_dias = $fecha_inicio->diffInDays($fecha_fin) + 1; // Sumar 1 para incluir ambos días

        // Actualizar la solicitud de vacaciones
        $vacacion->update([
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'duracion_dias' => $duracion_dias, // Guardar la nueva duración
            'estado' => $vacacion->estado, // Mantener el estado anterior
            'tipo_permiso_id' => $vacacion->tipo_permiso_id, // Mantener el tipo de permiso anterior
        ]);

        return redirect()->route('admin.vacaciones.index')->with('success', 'Solicitud de vacaciones actualizada correctamente.');
    }

    public function addComentario(Request $request, $id)
    {
        $vacacion = Vacacion::findOrFail($id);
        $vacacion->comentario = $request->comentario;
        $vacacion->save();

        return redirect()->route('admin.vacaciones.index');
    }
}
