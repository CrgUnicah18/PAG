<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vacacion;
use App\Models\Empleado;
use Illuminate\Support\Facades\Auth;
use App\Models\TipoPermiso;
use Carbon\Carbon;
use DateTime;
use Barryvdh\DomPDF\Facade\PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VacacionesExport;

class VacacionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user(); // Usuario autenticado
        $empleado = $user->empleado; // Accedemos al empleado a través de la relación
        $empleados = Empleado::all(); // Obtener todos los empleados
        $tiposVacaciones = TipoPermiso::where('es_vacacion', 1)->get();  // Filtra solo vacaciones

        // Calcular los días de vacaciones restantes para el empleado
        $vacacionesRestantes = $empleado->calcularBalanceVacaciones();

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
                ->paginate(8)
                ->appends(['nombreEmpleado' => $nombreEmpleado, 'estado' => $estado]);

            // Filtro para todas las solicitudes de vacaciones, con filtros de nombre y estado
            $vacacionesGenerales = Vacacion::when($estado, function ($query, $estado) {
                return $query->where('estado', $estado); // Filtrar por estado
            })
                ->when($nombreEmpleado, function ($query, $nombreEmpleado) {
                    return $query->whereHas('empleado', function ($query) use ($nombreEmpleado) {
                        $query->where('nombre', 'like', '%' . $nombreEmpleado . '%'); // Filtrar por nombre
                    });
                })
                ->paginate(8)
                ->appends(['nombreEmpleado' => $nombreEmpleado, 'estado' => $estado]); // Agregar filtros a la paginación

            return view('admin.vacaciones.index', compact('vacacionesPropias', 'vacacionesGenerales', 'empleados', 'tiposVacaciones', 'vacacionesRestantes', 'nombreEmpleado', 'estado'));
        }

        // Si el usuario es un supervisor
        if ($user->hasRole('supervisor')) {
            // Tabla 1: Solicitudes propias de vacaciones del supervisor (como empleado)
            $vacacionesPropias = Vacacion::where('empleado_id', $empleado->id)
                ->when($estado, function ($query, $estado) {
                    return $query->where('estado', $estado); // Filtrar por estado si es proporcionado
                })
                ->when($nombreEmpleado, function ($query, $nombreEmpleado) {
                    return $query->whereHas('empleado', function ($query) use ($nombreEmpleado) {
                        $query->where('nombre', 'like', '%' . $nombreEmpleado . '%'); // Filtrar por nombre de empleado
                    });
                })
                ->paginate(8)
                ->appends(['nombreEmpleado' => $nombreEmpleado, 'estado' => $estado]);

            // Tabla 2: Solicitudes de vacaciones de los empleados bajo la supervisión del supervisor
            $vacacionesGenerales = Vacacion::whereHas('empleado', function ($query) use ($empleado) {
                $query->where('supervisor_id', $empleado->id); // Filtra los empleados bajo la supervisión del supervisor
            })
                ->when($estado, function ($query, $estado) {
                    return $query->where('estado', $estado); // Filtrar por estado si es proporcionado
                })
                ->when($nombreEmpleado, function ($query, $nombreEmpleado) {
                    return $query->whereHas('empleado', function ($query) use ($nombreEmpleado) {
                        $query->where('nombre', 'like', '%' . $nombreEmpleado . '%'); // Filtrar por nombre de empleado
                    });
                })
                ->paginate(8)
                ->appends(['nombreEmpleado' => $nombreEmpleado, 'estado' => $estado]); // Agregar filtros a la paginación

            return view('supervisor.vacaciones.index', compact('vacacionesPropias', 'vacacionesGenerales', 'empleados', 'tiposVacaciones', 'vacacionesRestantes', 'nombreEmpleado', 'estado'));
        }

        // Si el usuario es un empleado
        if ($user->hasRole('empleado')) {
            // Tabla 1: Solicitudes propias de vacaciones del empleado
            $vacacionesPropias = Vacacion::where('empleado_id', $empleado->id)
                ->when($estado, function ($query, $estado) {
                    return $query->where('estado', $estado); // Filtrar por estado si es proporcionado
                })
                ->when($nombreEmpleado, function ($query, $nombreEmpleado) {
                    return $query->whereHas('empleado', function ($query) use ($nombreEmpleado) {
                        $query->where('nombre', 'like', '%' . $nombreEmpleado . '%'); // Filtrar por nombre de empleado
                    });
                })
                ->paginate(8)
                ->appends(['nombreEmpleado' => $nombreEmpleado, 'estado' => $estado]); // Agregar filtros a la paginación

            // No es necesario mostrar todas las solicitudes para un empleado
            $vacacionesGenerales = collect(); // Solo mostramos las propias del empleado

            return view('empleado.vacaciones.index', compact('vacacionesPropias', 'vacacionesGenerales', 'vacacionesRestantes', 'tiposVacaciones', 'nombreEmpleado', 'estado'));
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

        $fechaInicio = Carbon::parse($request->fecha_inicio);
        $fechaFin = Carbon::parse($request->fecha_fin);
        // Calcular duración de las vacaciones (en días)
        $duracionDias = $fechaInicio->diffInDays($fechaFin) + 1; // +1 para incluir el primer día

        // Verifica si las fechas fueron creadas correctamente
        if (!$fechaInicio || !$fechaFin) {
            // Si alguna de las fechas no es válida, muestra un mensaje de error
            return back()->withErrors('Las fechas no tienen el formato correcto (d-m-Y).');
        }

        // Obtener el empleado y calcular sus vacaciones restantes
        $empleado = Empleado::find($empleadoId);
        $vacacionesRestantes = $empleado->calcularBalanceVacaciones(); // Llamada al método de cálculo

        // Verificar si el empleado tiene suficientes vacaciones disponibles
        if ($vacacionesRestantes < $duracionDias) {
            return redirect()->back()->withErrors('No tienes suficientes días de vacaciones disponibles.');
        }

        // Obtener el tipo de permiso y su máximo de días permitidos
        $tipoPermiso = TipoPermiso::find($request->tipo_permiso_id);
        $maxDiasPermiso = $tipoPermiso->dias; // Asumiendo que tienes un campo 'dias' en la tabla 'tipo_permisos'

        // Validar si la duración total de días excede el máximo permitido por el tipo de permiso
        if ($duracionDias > $maxDiasPermiso) {
            return redirect()->back()->withInput()->withErrors([
                'duracion_excedida' => "El número de días solicitados ($duracionDias) supera el máximo permitido para este tipo de permiso ($maxDiasPermiso días)."
            ]);

        }


        // Lógica para crear la solicitud de vacaciones según el rol del usuario
        if ($user->hasRole('admin')) {
            if ($request->empleado_id == $empleadoId) {
                // Vacaciones del admin
                $diasSolicitados = $duracionDias;
                while ($diasSolicitados > 0) {
                    $diasParaSolicitar = min($diasSolicitados, $maxDiasPermiso);
                    $fechaFinParcial = $fechaInicio->copy()->addDays($diasParaSolicitar - 1);

                    // Crear la solicitud de vacaciones parcial
                    Vacacion::create([
                        'empleado_id' => $empleadoId,
                        'fecha_inicio' => $fechaInicio->toDateString(),
                        'fecha_fin' => $fechaFinParcial->toDateString(),
                        'duracion_dias' => $diasParaSolicitar,
                        'estado' => 'pendiente',
                        'tipo_permiso_id' => $request->tipo_permiso_id,
                        'comentario' => $request->comentario,
                    ]);

                    // Actualizar las fechas y días restantes
                    $fechaInicio = $fechaFinParcial->copy()->addDay();
                    $diasSolicitados -= $diasParaSolicitar;
                }

                // Descontar los días de vacaciones
                $empleado->vacaciones_restantes -= $duracionDias;
                $empleado->save();

                return redirect()->route('admin.vacaciones.index')->with('success', 'Solicitud de vacaciones propia creada correctamente.');
            } else {
                // Vacaciones de otro empleado
                $diasSolicitados = $duracionDias;
                while ($diasSolicitados > 0) {
                    $diasParaSolicitar = min($diasSolicitados, $maxDiasPermiso);
                    $fechaFinParcial = $fechaInicio->copy()->addDays($diasParaSolicitar - 1);

                    // Crear la solicitud de vacaciones parcial
                    Vacacion::create([
                        'empleado_id' => $request->empleado_id,
                        'fecha_inicio' => $fechaInicio->toDateString(),
                        'fecha_fin' => $fechaFinParcial->toDateString(),
                        'duracion_dias' => $diasParaSolicitar,
                        'estado' => 'aprobadas',
                        'tipo_permiso_id' => $request->tipo_permiso_id,
                        'comentario' => $request->comentario,
                    ]);

                    // Actualizar las fechas y días restantes
                    $fechaInicio = $fechaFinParcial->copy()->addDay();
                    $diasSolicitados -= $diasParaSolicitar;
                }

                // Actualizar estado de empleado
                $empleado = Empleado::find($request->empleado_id);
                $empleado->estado = 'inactivo'; // El empleado pasa a estar inactivo durante sus vacaciones
                $empleado->save();

                return redirect()->route('admin.vacaciones.index')->with('success', 'Solicitud de vacaciones para el empleado creada correctamente.');
            }
        }

        // Si el usuario es supervisor
        if ($user->hasRole('supervisor')) {
            $diasSolicitados = $duracionDias;
            while ($diasSolicitados > 0) {
                $diasParaSolicitar = min($diasSolicitados, $maxDiasPermiso);
                $fechaFinParcial = $fechaInicio->copy()->addDays($diasParaSolicitar - 1);

                // Crear la solicitud de vacaciones parcial
                Vacacion::create([
                    'empleado_id' => $request->empleado_id,
                    'fecha_inicio' => $fechaInicio->toDateString(),
                    'fecha_fin' => $fechaFinParcial->toDateString(),
                    'duracion_dias' => $diasParaSolicitar,
                    'estado' => 'pendiente',
                    'tipo_permiso_id' => $request->tipo_permiso_id,
                    'comentario' => $request->comentario,
                ]);

                // Actualizar las fechas y días restantes
                $fechaInicio = $fechaFinParcial->copy()->addDay();
                $diasSolicitados -= $diasParaSolicitar;
            }

            // Restamos los días de vacaciones porque está en estado pendiente
            $empleado = Empleado::find($request->empleado_id);
            $empleado->vacaciones_restantes -= $duracionDias;
            $empleado->save();

            return redirect()->route('supervisor.vacaciones.index')->with('success', 'Solicitud de vacaciones creada para el empleado.');
        }

        // Si el usuario es empleado
        if ($user->hasRole('empleado')) {
            $diasSolicitados = $duracionDias;
            while ($diasSolicitados > 0) {
                $diasParaSolicitar = min($diasSolicitados, $maxDiasPermiso);
                $fechaFinParcial = $fechaInicio->copy()->addDays($diasParaSolicitar - 1);

                // Crear la solicitud de vacaciones parcial
                Vacacion::create([
                    'empleado_id' => $empleadoId,
                    'fecha_inicio' => $fechaInicio->toDateString(),
                    'fecha_fin' => $fechaFinParcial->toDateString(),
                    'duracion_dias' => $diasParaSolicitar,
                    'estado' => 'pendiente',
                    'tipo_permiso_id' => $request->tipo_permiso_id,
                    'comentario' => $request->comentario,
                ]);

                // Actualizar las fechas y días restantes
                $fechaInicio = $fechaFinParcial->copy()->addDay();
                $diasSolicitados -= $diasParaSolicitar;
            }

            // Restamos los días de vacaciones porque está en estado pendiente
            $empleado->vacaciones_restantes -= $duracionDias;
            $empleado->save();

            return redirect()->route('empleado.vacaciones.index')->with('success', 'Solicitud de vacaciones enviada correctamente.');
        }
    }

    public function create()
    {
        $user = auth()->user();
        $empleado = $user->empleado; // Obtener el empleado relacionado con el usuario
        // Calcular las vacaciones restantes
        $vacacionesRestantes = $empleado->calcularBalanceVacaciones();

        // Verificar el rol para redirigir a las vistas correspondientes
        if ($user->hasRole('admin')) {
            $empleados = Empleado::all(); // Obtener todos los empleados si es admin
            return view('admin.vacaciones.create', compact('empleados', 'vacacionesRestantes'));
        }

        if ($user->hasRole('supervisor')) {
            $empleados = Empleado::where('supervisor_id', $user->empleado_id)->get(); // Solo los empleados bajo el supervisor
            return view('supervisor.vacaciones.create', compact('empleados', 'vacacionesRestantes'));
        }

        if ($user->hasRole('empleado')) {
            return view('empleado.vacaciones.create', compact('vacacionesRestantes'));
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

                return view('supervisor.vacaciones.index', compact('vacacionesPropias', 'vacacionesGenerales'))->with('success', 'Pre-aprobadas por supervisor.');
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

            // Aquí se aprueba la solicitud
            $vacacion->update(['estado' => 'aprobadas']);

            // Cambiar el estado del empleado a inactivo si la vacación es aprobada
            $empleado = Empleado::find($vacacion->empleado_id); // Asumiendo que $solicitud tiene el empleado_id
            $empleado->estado = 'inactivo'; // Cambiar a inactivo
            $empleado->save();

            // Ahora calculamos el balance de vacaciones
            $vacacionesRestantes = $empleado->calcularBalanceVacaciones(); // Suponiendo que tienes este método
            $empleado->vacaciones_restantes = $vacacionesRestantes; // Actualizamos el saldo de vacaciones
            $empleado->save(); // Guardamos el cambio

            // Obtener las vacaciones generales y las del supervisor
            $vacacionesGenerales = Vacacion::paginate(8); // o el número que quieras
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

        // Si la solicitud aún no está rechazada
        if ($vacacion->estado !== 'rechazadas') {

            // SUPERVISOR
            if ($user->hasRole('supervisor') && in_array($vacacion->estado, ['pendiente', 'pendientes_aprobacion'])) {
                if ($vacacion->empleado->supervisor_id == $user->empleado->id) {

                    // Restaurar las vacaciones antes de rechazar
                    $vacacion->empleado->restaurarVacaciones($vacacion->dias_solicitados);

                    // Cambiar estado a rechazadas
                    $vacacion->estado = 'rechazadas';
                    $vacacion->save();

                    return redirect()->route('supervisor.vacaciones.index')
                        ->with('success', 'Vacaciones rechazadas exitosamente por el supervisor.');
                } else {
                    return redirect()->back()->with('error', 'No puedes rechazar las vacaciones de un empleado que no te está asignado.');
                }
            }

            // ADMIN
            if ($user->hasRole('admin') && in_array($vacacion->estado, ['pendiente', 'pendientes_aprobacion'])) {
                if ($vacacion->empleado_id == $user->empleado->id) {
                    return redirect()->back()->with('error', 'No puedes rechazar tu propia solicitud de vacaciones.');
                }

                // Restaurar las vacaciones antes de rechazar
                $vacacion->empleado->restaurarVacaciones($vacacion->dias_solicitados);

                // Cambiar estado a rechazadas
                $vacacion->estado = 'rechazadas';
                $vacacion->save();

                $vacacionesGenerales = Vacacion::all();
                return redirect()->route('admin.vacaciones.index')
                    ->with(compact('vacacionesGenerales'))
                    ->with('success', 'Vacaciones rechazadas exitosamente por el admin.');
            }

        } else {
            return redirect()->back()->with('info', 'Esta solicitud ya había sido rechazada anteriormente.');
        }

        return redirect()->back()->with('error', 'Esta solicitud no puede ser rechazada en este momento.');
    }





    public function addComentario(Request $request, Vacacion $vacacion)
    {
        $request->validate(['comentario' => 'required|string']);

        $vacacion->update(['comentario' => $request->comentario]);

        return back()->with('success', 'Comentario agregado correctamente.');
    }

    public function actualizarEstadoEmpleadosPorVacaciones()
    {
        $hoy = now()->toDateString();

        // Activar empleados cuya vacaciones ya terminaron
        $vacacionesFinalizadas = \App\Models\Vacacion::where('fecha_fin', '<', $hoy)->get();

        foreach ($vacacionesFinalizadas as $vacacion) {
            $empleado = $vacacion->empleado;

            if ($empleado && $empleado->estado === 'inactivo') {
                $empleado->estado = 'activo';
                $empleado->save();
            }
        }

        // Inactivar empleados con vacaciones activas hoy
        $vacacionesActivas = \App\Models\Vacacion::where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy)
            ->get();

        foreach ($vacacionesActivas as $vacacion) {
            $empleado = $vacacion->empleado;

            if ($empleado && $empleado->estado !== 'inactivo') {
                $empleado->estado = 'inactivo';
                $empleado->save();
            }
        }


    }
    // Mostrar el formulario de filtros para el reporte de vacaciones
    public function mostrarFormularioReporte()
    {
        $empleados = Empleado::all(); // Si necesitas una lista de empleados para mostrar en el formulario
        return view('admin.vacaciones.formulario-reporte', compact('empleados'));
    }

    // Generar el reporte según los filtros seleccionados
    public function generarReporte(Request $request)
    {
        $query = Vacacion::query();

        // Si se seleccionó un empleado, filtrar por empleado
        if ($request->filled('empleado_id')) {
            $query->where('empleado_id', $request->empleado_id);
        }

        // Si se seleccionaron fechas, filtrar por las fechas
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_fin]);
        }

        // Si se seleccionó un estado, filtrar por estado
        if ($request->filled('estado')) {
            $query->whereIn('estado', $request->estado); // Cambié `where` por `whereIn` para múltiples estados
        }

        // Obtener los resultados de las vacaciones con paginación
        $vacaciones = $query->paginate(10);

        // Campos seleccionados para mostrar en el reporte
        $camposSeleccionados = $request->input('campos', [
            'empleado_id',
            'fecha_inicio',
            'fecha_fin',
            'duracion_dias',
            'estado',
            'tipo_permiso_id',
            'comentario'
        ]);

        // Pasar los resultados a la vista
        return view('admin.vacaciones.reporte', compact('vacaciones', 'camposSeleccionados'))
            ->with('filtros', $request->all()); // Pasamos los filtros a la vista
    }

    public function exportarPDF(Request $request)
    {
        // Aplicar los filtros de la misma manera que en generarReporte
        $query = Vacacion::query();

        $empleadoSeleccionado = null;
        if ($request->filled('empleado_id')) {
            $empleadoSeleccionado = Empleado::find($request->empleado_id);
        }


        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_fin]);
        }

        if ($request->filled('estado')) {
            $query->whereIn('estado', $request->estado);
        }

        // Obtener las vacaciones filtradas con paginación
        $vacaciones = $query->get(); // Usamos paginación aquí

        // Obtener los campos seleccionados, o usar valores predeterminados
        $camposSeleccionados = $request->input('campos', [
            'empleado_id',
            'fecha_inicio',
            'fecha_fin',
            'duracion_dias',
            'estado',
            'tipo_permiso_id',
            'comentario'
        ]);

        // Pasar los datos a la vista para generar el PDF
        // Generar el PDF usando la vista exclusiva con los campos seleccionados
        $pdf = PDF::loadView('admin.vacaciones.reporte_pdf', [
            'vacaciones' => $vacaciones,
            'camposSeleccionados' => $camposSeleccionados,
            'empleadoSeleccionado' => $empleadoSeleccionado ?? null, // le pasás null si no hay filtro por empleado
        ]);


        // Descargar el PDF
        return $pdf->download('reporte_vacaciones.pdf');
    }


    public function exportarExcel(Request $request)
    {
        return Excel::download(new VacacionesExport($request), 'reporte_vacaciones.xlsx');
    }





}
