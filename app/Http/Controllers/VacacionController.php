<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vacacion;
use App\Models\Empleado;
use Illuminate\Support\Facades\Auth;
use App\Models\TipoPermiso;
use Carbon\Carbon;

class VacacionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user(); // Usuario autenticado
        $empleado = $user->empleado; // Accedemos al empleado a través de la relación
        $empleados = Empleado::all(); // Obtener todos los empleados
        $tiposVacaciones = TipoPermiso::where('es_vacacion', 1)->get();  // Filtra solo vacaciones

        // Variables para los filtros de nombre de empleado y estado
        $nombreEmpleado = $request->input('nombreEmpleado');
        $estado = $request->input('estado');

        // Si el usuario es un administrador
        if ($user->hasRole('admin')) {
            // Filtro para vacaciones propias del admin (solo este empleado)
            $vacacionesPropias = Vacacion::where('empleado_id', $empleado->id)
                ->when($estado, function ($query, $estado) {
                    return $query->where('estado', $estado); // Filtrar por estado si es proporcionado
                })
                ->when($nombreEmpleado, function ($query, $nombreEmpleado) {
                    return $query->whereHas('empleado', function ($query) use ($nombreEmpleado) {
                        $query->where('nombre', 'like', '%' . $nombreEmpleado . '%'); // Filtrar por nombre de empleado
                    });
                })
                ->paginate(8);

            // Filtro para todas las solicitudes de vacaciones, con filtros de nombre y estado
            $vacacionesGenerales = Vacacion::when($estado, function ($query, $estado) {
                return $query->where('estado', $estado); // Filtrar por estado
            })
                ->when($nombreEmpleado, function ($query, $nombreEmpleado) {
                    return $query->whereHas('empleado', function ($query) use ($nombreEmpleado) {
                        $query->where('nombre', 'like', '%' . $nombreEmpleado . '%'); // Filtrar por nombre
                    });
                })
                ->paginate(8); // Paginación para todas las vacaciones

            return view('admin.vacaciones.index', compact('vacacionesPropias', 'vacacionesGenerales', 'empleados', 'tiposVacaciones', 'nombreEmpleado', 'estado'));
        }
        // Si el usuario es un supervisor
        if ($user->hasRole('supervisor')) {
            // Tabla 1: Solicitudes propias de vacaciones del supervisor (como empleado)
            $vacacionesPropias = Vacacion::where('empleado_id', $empleado->id)->get();

            // Tabla 2: Solicitudes de vacaciones de los empleados bajo la supervisión del supervisor
            $vacacionesGenerales = Vacacion::whereHas('empleado', function ($query) use ($empleado) {
                $query->where('supervisor_id', $empleado->id); // Filtra los empleados bajo la supervisión del supervisor
            })->get();

            return view('supervisor.vacaciones.index', compact('vacacionesPropias', 'vacacionesGenerales', 'empleados', 'tiposVacaciones', 'tiposVacaciones'));
        }

        // Si el usuario es un empleado
        if ($user->hasRole('empleado')) {
            // Tabla 1: Solicitudes propias de vacaciones del empleado
            $vacacionesPropias = Vacacion::where('empleado_id', $empleado->id)->get();

            // No es necesario mostrar todas las solicitudes para un empleado
            $vacacionesGenerales = collect(); // Solo mostramos las propias del empleado

            return view('empleado.vacaciones.index', compact('vacacionesPropias', 'vacacionesGenerales'));
        }

        // Si no tiene un rol válido
        return abort(403, 'No tienes permiso para ver esta página');
    }

    public function store(Request $request)
    {
        // Validación común para todos los tipos de solicitud de vacaciones
        $request->validate([
            'fecha_inicio' => 'required|date|after_or_equal:' . Carbon::today()->toDateString(),
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'tipo_permiso_id' => 'required|exists:tipo_permisos,id', // Validación para tipo_permiso_id
            'comentario' => 'nullable|string|max:255', // Validación para comentario
        ]);

        $user = auth()->user();
        $empleadoId = $user->empleado_id;

        // Verificar que no haya solicitudes con fechas sobrepuestas para el mismo empleado
        $solicitudesExistentes = Vacacion::where('empleado_id', $empleadoId)
            ->whereIn('estado', ['pendiente', 'aprobadas', 'pendientes_aprobacion'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_fin])
                    ->orWhereBetween('fecha_fin', [$request->fecha_inicio, $request->fecha_fin])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('fecha_inicio', '<=', $request->fecha_inicio)
                            ->where('fecha_fin', '>=', $request->fecha_fin);
                    });
            })
            ->exists();

        // Si existen solicitudes con fechas sobrepuestas, retornar un error
        if ($solicitudesExistentes) {
            return redirect()->back()->withErrors('Ya existe una solicitud de vacaciones con fechas sobrepuestas.');
        }

        // Si el usuario es admin, puede crear solicitudes para él mismo o para cualquier empleado
        if ($user->hasRole('admin')) {
            // Si la solicitud es para el admin (vacaciones propias)
            if ($request->empleado_id == $empleadoId) {
                Vacacion::create([
                    'empleado_id' => $empleadoId, // El admin crea una solicitud para sí mismo
                    'fecha_inicio' => $request->fecha_inicio,
                    'fecha_fin' => $request->fecha_fin,
                    'duracion_dias' => (new \DateTime($request->fecha_inicio))->diff(new \DateTime($request->fecha_fin))->days + 1,
                    'estado' => 'pendiente',
                    'tipo_permiso_id' => $request->tipo_permiso_id, // Asegúrate de usar tipo_permiso_id
                    'comentario' => $request->comentario, // Agregamos comentario
                ]);
                return redirect()->route('admin.vacaciones.index')->with('success', 'Solicitud de vacaciones propia creada correctamente.');
            }
            // Si la solicitud es para otro empleado (administrador crea solicitud)
            else {
                Vacacion::create([
                    'empleado_id' => $request->empleado_id,
                    'fecha_inicio' => $request->fecha_inicio,
                    'fecha_fin' => $request->fecha_fin,
                    'duracion_dias' => (new \DateTime($request->fecha_inicio))->diff(new \DateTime($request->fecha_fin))->days + 1,
                    'estado' => 'aprobadas', // Poner estado como 'aprobado' automáticamente
                    'tipo_permiso_id' => $request->tipo_permiso_id, // Asegúrate de usar tipo_permiso_id
                    'comentario' => $request->comentario, // Agregamos comentario
                ]);
                return redirect()->route('admin.vacaciones.index')->with('success', 'Solicitud de vacaciones para el empleado creada correctamente.');
            }
        }

        // Si el usuario es supervisor
        if ($user->hasRole('supervisor')) {
            Vacacion::create([
                'empleado_id' => $request->empleado_id,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'duracion_dias' => (new \DateTime($request->fecha_inicio))->diff(new \DateTime($request->fecha_fin))->days + 1,
                'estado' => 'pendiente', // El supervisor lo pone como pendiente
                'tipo_permiso_id' => $request->tipo_permiso_id, // Asegúrate de usar tipo_permiso_id
                'comentario' => $request->comentario, // Agregamos comentario
            ]);
            return redirect()->route('supervisor.vacaciones.index')->with('success', 'Solicitud de vacaciones creada para el empleado.');
        }

        // Si el usuario es empleado
        if ($user->hasRole('empleado')) {
            Vacacion::create([
                'empleado_id' => $empleadoId,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'duracion_dias' => (new \DateTime($request->fecha_inicio))->diff(new \DateTime($request->fecha_fin))->days + 1,
                'estado' => 'pendiente', // El estado lo inicializamos como pendiente
                'tipo_permiso_id' => $request->tipo_permiso_id, // Asegúrate de usar tipo_permiso_id
                'comentario' => $request->comentario, // Agregamos comentario
            ]);
            return redirect()->route('empleado.vacaciones.index')->with('success', 'Solicitud de vacaciones enviada correctamente.');
        }
    }

    public function create()
    {
        $user = auth()->user();

        // Verificar el rol para redirigir a las vistas correspondientes
        if ($user->hasRole('admin')) {
            $empleados = Empleado::all(); // Obtener todos los empleados si es admin
            return view('admin.vacaciones.create', compact('empleados'));
        }

        if ($user->hasRole('supervisor')) {
            $empleados = Empleado::where('supervisor_id', $user->empleado_id)->get(); // Solo los empleados bajo el supervisor
            return view('supervisor.vacaciones.create', compact('empleados'));
        }

        if ($user->hasRole('empleado')) {
            return view('empleado.vacaciones.create');
        }
    }

    public function update(Request $request, Vacacion $vacacion)
    {
        $user = auth()->user();

        // Solo el supervisor o admin puede cambiar el estado de las vacaciones
        if (!($user->hasRole('supervisor') || $user->hasRole('admin'))) {
            return abort(403); // Acceso denegado
        }

        // Validación del estado
        $request->validate([
            'estado' => 'required|in:pendiente,pendientes_aprobacion,aprobadas,rechazadas',
        ]);

        // Actualizar el estado de la solicitud
        $vacacion->update(['estado' => $request->estado]);

        // Redirigir dependiendo del rol
        if ($user->hasRole('supervisor')) {
            return redirect()->route('supervisor.vacaciones.index')->with('success', 'Estado de vacaciones actualizado.');
        }

        return redirect()->route('admin.vacaciones.index')->with('success', 'Estado de vacaciones actualizado.');
    }

    // VacacionController.php
    public function aprobar($id)
    {
        $vacacion = Vacacion::findOrFail($id);
        $user = auth()->user();
        $empleado = $user->empleado; // Accedemos al empleado a través de la relación
        // Obtener empleados (si es necesario)
        $empleados = Empleado::all();

        // El supervisor puede pre-aprobar, solo si el estado es 'pendiente'
        if ($user->hasRole('supervisor') && $vacacion->estado == 'pendiente') {
            // Filtrar las vacaciones solo de los empleados asignados al supervisor
            if ($vacacion->empleado->supervisor_id == $user->empleado->id) {
                $vacacion->update(['estado' => 'pendientes_aprobacion']);
                $vacacionesPropias = Vacacion::where('empleado_id', $empleado->id)->get();
                $vacacionesGenerales = Vacacion::whereHas('empleado', function ($query) use ($empleado) {
                    $query->where('supervisor_id', $empleado->id); // Filtra los empleados bajo la supervisión del supervisor
                })->get();

                return view('supervisor.vacaciones.index', compact('vacacionesPropias', 'vacacionesGenerales'))->with('success', 'pre-aprobadas por supervisor.');
            } else {
                return redirect()->back()->with('error', 'No puedes aprobar las vacaciones de un empleado que no te está asignado.');
            }
        }

        $tiposVacaciones = TipoPermiso::where('es_vacacion', 1)->get();  // Obtener solo tipos de vacaciones

        // El admin puede aprobar, solo si el estado es 'pendientes_aprobacion' o 'pendiente'
        if ($user->hasRole('admin') && in_array($vacacion->estado, ['pendiente', 'pendientes_aprobacion'])) {
            // Evitar que el admin apruebe su propia solicitud de vacaciones
            if ($vacacion->empleado_id == $user->empleado->id) {
                return redirect()->back()->with('error', 'No puedes aprobar tu propia solicitud de vacaciones.');
            }

            $vacacion->update(['estado' => 'aprobadas']);
            $vacacionesGenerales = Vacacion::paginate(8); // o el número que querás
            // Obtener todas las vacaciones

            $vacacionesPropias = Vacacion::where('empleado_id', $user->empleado->id)->paginate(8);

            return view('admin.vacaciones.index', compact('vacacionesGenerales', 'empleados', 'tiposVacaciones', 'vacacionesPropias'))
                ->with('success', 'Aprobadas por Admin.');

        }
        // Si no se cumplen las condiciones
        return redirect()->back()->with('error', 'Esta solicitud no puede ser aprobada en este momento.');
    }



    public function rechazar($id)
    {
        $vacacion = Vacacion::findOrFail($id);
        $user = auth()->user();

        // El supervisor puede rechazar, solo si el estado es 'pendiente' o 'pendientes_aprobacion'
        if ($user->hasRole('supervisor') && in_array($vacacion->estado, ['pendiente', 'pendientes_aprobacion'])) {
            // Filtrar las vacaciones solo de los empleados asignados al supervisor
            if ($vacacion->empleado->supervisor_id == $user->empleado->id) {
                $vacacion->update(['estado' => 'rechazadas']);
                return redirect()->route('supervisor.vacaciones.index')->with('success', 'Rechazadas por supervisor.');
            } else {
                return redirect()->back()->with('error', 'No puedes rechazar las vacaciones de un empleado que no te está asignado.');
            }
        }

        // El admin puede rechazar, solo si el estado es 'pendiente' o 'pendientes_aprobacion'
        if ($user->hasRole('admin') && in_array($vacacion->estado, ['pendiente', 'pendientes_aprobacion'])) {
            // Evitar que el admin rechace su propia solicitud de vacaciones
            if ($vacacion->empleado_id == $user->empleado->id) {
                return redirect()->back()->with('error', 'No puedes rechazar tu propia solicitud de vacaciones.');
            }

            $vacacion->update(['estado' => 'rechazadas']);
            $vacacionesGenerales = Vacacion::all();  // Obtener todas las vacaciones
            return redirect()->route('admin.vacaciones.index')->with(compact('vacacionesGenerales'))->with('success', 'Rechazadas por Admin.');
        }


        // Si no se cumplen las condiciones
        return redirect()->back()->with('error', 'Esta solicitud no puede ser rechazada en este momento.');
    }





    public function addComentario(Request $request, Vacacion $vacacion)
    {
        $request->validate(['comentario' => 'required|string']);

        $vacacion->update(['comentario' => $request->comentario]);

        return back()->with('success', 'Comentario agregado correctamente.');
    }
}
