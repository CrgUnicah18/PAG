<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Empleado;
use App\Models\TipoPermiso;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VacacionesExport;

class PermisoController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $empleado = Empleado::find($user->empleado_id);

        $estado = $request->input('estado');
        $nombreEmpleado = $request->nombreEmpleado;

        if (!$empleado) {
            return redirect()->back()->with('error', 'El empleado no existe en el sistema.');
        }

        $permisosSupervisor = [];
        $permisosEmpleados = [];
        $empleadosBajoSupervision = [];
        $permisosEmpleado = [];
        $permisosAdmin = [];

        $empleados = Empleado::all();

        $empleado = $user->empleado;

        // SUPERVISOR
        if ($user->hasRole('supervisor')) {
            $empleadosBajoSupervision = Empleado::where('supervisor_id', $empleado->id)->get();

            if ($empleadosBajoSupervision->isNotEmpty()) {
                $permisosEmpleados = Permiso::whereIn('empleado_id', $empleadosBajoSupervision->pluck('id'))
                    ->when($nombreEmpleado, function ($query, $nombreEmpleado) {
                        return $query->whereHas('empleado', function ($query) use ($nombreEmpleado) {
                            $query->where('nombre', 'like', '%' . $nombreEmpleado . '%');
                        });
                    })
                    ->when($estado, function ($query, $estado) {
                        return $query->where('estado', $estado);
                    })
                    ->orderBy('created_at', 'desc') // ← ORDEN NUEVO
                    ->paginate(10);
            } else {
                $permisosEmpleados = collect();
            }

            $permisosSupervisor = Permiso::where('empleado_id', $empleado->id)
                ->orderBy('created_at', 'desc') // ← ORDEN NUEVO
                ->paginate(10);

            return view('supervisor.permisos.index', compact('empleadosBajoSupervision', 'permisosSupervisor', 'permisosEmpleados', 'empleados'));
        }

        // EMPLEADO
        if ($user->hasRole('empleado')) {
            $permisosEmpleado = Permiso::where('empleado_id', $empleado->id)
                ->orderBy('created_at', 'desc') // ← ORDEN NUEVO
                ->paginate(10);

            return view('empleado.permisos.index', compact('permisosEmpleado', 'empleados'));
        }

        // ADMIN
        if ($user->hasRole('admin')) {
            $permisosAdmin = Permiso::where('empleado_id', $user->empleado->id)
                ->orderBy('created_at', 'desc') // ← ORDEN NUEVO
                ->paginate(10);

            $permisos = Permiso::when($estado, function ($query, $estado) {
                return $query->where('estado', $estado);
            })
                ->when($nombreEmpleado, function ($query, $nombreEmpleado) {
                    return $query->whereHas('empleado', function ($q) use ($nombreEmpleado) {
                        $q->where('nombre', 'like', '%' . $nombreEmpleado . '%');
                    });
                })
                ->orderBy('created_at', 'desc') // ← ORDEN NUEVO
                ->paginate(10);

            return view('admin.permisos.index', compact('permisos', 'permisosAdmin', 'empleados', 'nombreEmpleado', 'estado'));
        }

        return redirect()->back()->with('error', 'No tienes acceso a esta sección.');
    }


    // FIXME: Mostrar el formulario para solicitar un permiso
    public function create()
    {
        $user = auth()->user(); // Usuario logueado

        $tiposPermiso = TipoPermiso::where(function ($query) use ($user) {
            $query->where('es_vacacion', 0); // Siempre excluir vacaciones

            // Agregar permisos por género
            if ($user->genero === 'F') {
                $query->orWhere(function ($q) {
                    $q->where('es_licencia', 1)->where('es_vacacion', 0);
                });
            } elseif ($user->genero === 'M') {
                $query->orWhere(function ($q) {
                    $q->where('es_licenciam', 1)->where('es_vacacion', 0);
                });
            }
        })->get();

        // Cargar vista según rol
        if ($user->hasRole('supervisor')) {
            return view('supervisor.permisos.create', compact('tiposPermiso'));
        }

        if ($user->hasRole('empleado')) {
            return view('empleado.permisos.create', compact('tiposPermiso'));
        }

        if ($user->hasRole('admin')) {
            return view('admin.permisos.create', compact('tiposPermiso'));
        }

        return redirect()->back()->with('error', 'No tienes acceso a esta sección.');
    }



    public function store(Request $request)
    {
        // Validar los campos de la solicitud
        $request->validate([
            'tipo_permiso_id' => 'required|exists:tipo_permisos,id',
            'fecha_inicio' => 'required|date|after_or_equal:' . Carbon::today()->toDateString(),
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'comentario' => 'nullable|string|max:255',
        ]);

        // Obtener el usuario logueado
        $user = auth()->user();

        // Obtener el empleado correspondiente al usuario logueado usando el 'empleado_id' en la tabla 'users'
        $empleado = Empleado::find($user->empleado_id);

        // Verificar si el empleado existe
        if (!$empleado) {
            return redirect()->back()->with('error', 'El empleado no existe en el sistema.');
        }

        // ** Validación 1: Verificar el máximo de 2 solicitudes por mes **
        // ! IMPORTANTE: Solo se deben contar los permisos que estén en estado 'pendiente', 'pendiente_aprobacion' o 'aprobado'
        // !ya que los permisos 'rechazados' NO deben contabilizarse como parte del límite mensual.
        // !Esto permite que un empleado pueda volver a solicitar otro permiso si uno anterior fue rechazado.

        // TODO Validación 1: Verificar el máximo de 2 solicitudes por mes (excepto los rechazados)
        $permisosEsteMes = Permiso::where('empleado_id', $empleado->id)
            ->whereYear('fecha_inicio', Carbon::now()->year)
            ->whereMonth('fecha_inicio', Carbon::now()->month)
            ->whereIn('estado', ['pendiente', 'pendiente_aprobacion', 'aprobado']) // Excluye los rechazados
            ->count();

        if ($permisosEsteMes >= 2) {
            return redirect()->back()->with('error', 'Ya has alcanzado el límite de 2 permisos por mes.');
        }


        // ** Validación 2: Verificar el máximo de 15 permisos por año **
        $permisosEsteAnio = Permiso::where('empleado_id', $empleado->id)
            ->whereYear('fecha_inicio', Carbon::now()->year) // Filtrar por el año actual
            ->count();

        if ($permisosEsteAnio >= 15) {
            return redirect()->back()->with('error', 'Ya has alcanzado el límite de 15 permisos por año.');
        }

        // ** Validación 3: Verificar si el tipo de permiso no es vacación y la duración excede el máximo permitido **
        $tipoPermiso = TipoPermiso::find($request->tipo_permiso_id);

        // Verificar si el tipo de permiso no es vacación (es_vacacion == 0)
        if ($tipoPermiso && $tipoPermiso->es_vacacion == 0) {
            // Obtener el número máximo de días permitidos para este tipo de permiso (campo 'dias')
            $diasMaximos = $tipoPermiso->dias; // Campo 'dias' de la tabla 'tipo_permisos'

            // Calcular la duración del permiso solicitado
            $diasDuracion = Carbon::parse($request->fecha_inicio)->diffInDays(Carbon::parse($request->fecha_fin)) + 1; // +1 para incluir el día de inicio

            if ($diasDuracion > $diasMaximos) {
                return redirect()->back()->with('error', 'El permiso no puede exceder los ' . $diasMaximos . ' días.');
            }
        }

        // Verificar si hay permisos pendientes o pendientes_aprobacion con fechas sobrepuestas para el empleado
        $fechasSobrepuestas = Permiso::where('empleado_id', $empleado->id)
            ->whereIn('estado', ['pendiente', 'pendiente_aprobacion', 'aprobado']) // Verificar ambos estados
            ->where(function ($query) use ($request) {
                $query->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_fin])
                    ->orWhereBetween('fecha_fin', [$request->fecha_inicio, $request->fecha_fin])
                    ->orWhere(function ($query2) use ($request) {
                        $query2->where('fecha_inicio', '<=', $request->fecha_inicio)
                            ->where('fecha_fin', '>=', $request->fecha_fin);
                    });
            })
            ->exists();

        if ($fechasSobrepuestas) {
            return redirect()->back()->with('error', 'Ya tienes una solicitud de permiso pendiente en las fechas seleccionadas.');
        }

        // Crear el nuevo permiso
        $permiso = new Permiso();
        $permiso->empleado_id = $empleado->id;  // Usar el ID del empleado relacionado con el usuario
        $permiso->tipo_permiso_id = $request->tipo_permiso_id;
        $permiso->fecha_inicio = $request->fecha_inicio;
        $permiso->fecha_fin = $request->fecha_fin;
        $permiso->estado = 'pendiente';  // Estado inicial
        $permiso->comentario = $request->comentario;
        // Eliminar esta línea porque 'es_licencia' no debe ir en la tabla 'permisos'
        $permiso->save();

        // Redirigir según el rol del usuario
        if ($user->hasRole('supervisor')) {
            return redirect()->route('supervisor.permisos.index')->with('success', 'Solicitud de permiso enviada.');
        }
        if ($user->hasRole('empleado')) {
            return redirect()->route('empleado.permisos.index')->with('success', 'Solicitud de permiso enviada.');
        }
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.permisos.index')->with('success', 'Solicitud de permiso enviada.');
        }

        return redirect()->back()->with('error', 'No tienes acceso para crear un permiso.');
    }

    // FIXME: Aprobar un permiso (Supervisor o Admin)
    public function aprobar($id)
    {
        $permiso = Permiso::findOrFail($id);
        $user = auth()->user();

        // El supervisor puede pre-aprobar, solo si el estado es 'pendiente'
        if ($user->hasRole('supervisor') && $permiso->estado == 'pendiente') {
            $permiso->update(['estado' => 'pendiente_aprobacion']);
            return redirect()->route('supervisor.permisos.index')->with('success', 'Pre-aprobado por supervisor.');
        }

        // El admin puede aprobar, solo si el estado es 'pendiente_aprobacion' o 'pendiente'
        if ($user->hasRole('admin') && ($permiso->estado == 'pendiente_aprobacion' || $permiso->estado == 'pendiente')) {
            // Evitar que el admin apruebe su propio permiso
            if ($permiso->empleado_id == $user->empleado->id) {
                return redirect()->back()->with('error', 'No puedes aprobar tu propio permiso.');
            }

            $permiso->update(['estado' => 'aprobado']);

            // Solo cambiar a inactivo si la fecha de inicio del permiso es hoy o ya pasó
            if (Carbon::parse($permiso->fecha_inicio)->lte(now())) {
                $empleado = $permiso->empleado;
                $empleado->update(['estado' => 'inactivo']);
            }

            return redirect()->route('admin.permisos.index')->with('success', 'Aprobado por Admin.');
        }

        // Si no se cumplen las condiciones
        return redirect()->back()->with('error', 'Este permiso no puede ser aprobado en este momento.');
    }


    // Rechazar un permiso (Supervisor o Admin)
    public function declinar($id)
    {
        $permiso = Permiso::findOrFail($id);
        $user = auth()->user();

        // El supervisor puede rechazar, solo si el estado es 'pendiente' o 'pendiente_aprobacion'
        if ($user->hasRole('supervisor') && in_array($permiso->estado, ['pendiente', 'pendiente_aprobacion'])) {

            $permiso->update(['estado' => 'rechazado']);
            return redirect()->route('supervisor.permisos.index')->with('success', 'Rechazado por supervisor.');
        }

        // El admin puede rechazar, solo si el estado es 'pendiente' o 'pendiente_aprobacion'
        if ($user->hasRole('admin') && in_array($permiso->estado, ['pendiente', 'pendiente_aprobacion'])) {
            // Evitar que el admin apruebe su propio permiso
            if ($permiso->empleado_id == $user->empleado->id) {
                return redirect()->back()->with('error', 'No puedes aprobar tu propio permiso.');
            }

            $permiso->update(['estado' => 'rechazado']);
            return redirect()->route('admin.permisos.index')->with('success', 'Rechazado por Admin.');
        }

        // Si no se cumplen las condiciones
        return redirect()->back()->with('error', 'Este permiso no puede ser rechazado en este momento.');
    }

    public function comentar(Request $request, $permisoId)
    {
        // Validar que el comentario no esté vacío
        $request->validate([
            'comentario' => 'required|string|max:500',
        ]);

        // Buscar el permiso y agregar el comentario
        $permiso = Permiso::findOrFail($permisoId);
        $permiso->comentario = $request->comentario;
        $permiso->save();

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->back()->with('success', 'Comentario guardado exitosamente.');
    }

    public function actualizarEstadoEmpleados()
    {
        // Obtener todos los permisos aprobados
        $permisos = Permiso::where('estado', 'aprobado')->get();

        foreach ($permisos as $permiso) {
            // Verificar si la fecha de fin del permiso ha pasado
            if ($permiso->fecha_fin < now()) {
                // Cambiar el estado del empleado a activo
                $empleado = $permiso->empleado;
                $empleado->estado = 'activo'; // Cambiar a 'activo'
                $empleado->save();
            }
        }

        return response()->json(['message' => 'Estado de los empleados actualizado.']);
    }

    // Mostrar formulario para generar el reporte
    public function mostrarFormulario()
    {
        // Obtener todos los empleados
        $empleados = Empleado::all();

        // Obtener los tipos de permisos (solo los que no son vacaciones)
        $tiposPermiso = TipoPermiso::where('es_vacacion', 0)->get();

        // Pasar los empleados y tipos de permiso a la vista del formulario
        return view('admin.permisos.reporte_formulario', compact('empleados', 'tiposPermiso'));
    }

    public function generarReporte(Request $request)
    {
        $empleadoId = $request->input('empleado_id');
        $tipoPermisoId = $request->input('tipo_permiso_id');
        $mes = $request->input('mes');
        $anio = $request->input('anio');

        $query = Permiso::query();

        // Filtros de búsqueda
        if ($empleadoId && $empleadoId != 'todos') {
            $query->where('empleado_id', $empleadoId);
        }

        if ($tipoPermisoId && $tipoPermisoId != 'todos') {
            $query->where('tipo_permiso_id', $tipoPermisoId);
        }

        $query->whereYear('fecha_inicio', $anio);

        if ($mes && $mes != 'todos') {
            $query->whereMonth('fecha_inicio', $mes);
        }

        // Obtener los permisos con paginación (5 por página)
        $permisos = $query->with('empleado')
            ->join('tipo_permisos', 'permisos.tipo_permiso_id', '=', 'tipo_permisos.id')
            ->select('permisos.*', 'tipo_permisos.nombre as tipo_permiso')
            ->orderBy('fecha_inicio')
            ->paginate(5); // Aquí usamos paginate()

        // Obtener el empleado si fue filtrado
        $empleado = $empleadoId && $empleadoId != 'todos' ? Empleado::findOrFail($empleadoId) : null;

        // Se indica que no es para PDF
        $isPdf = false;

        // Mostrar la vista del reporte
        return view('admin.permisos.reporte_pdf', compact('permisos', 'empleado', 'empleadoId', 'tipoPermisoId', 'mes', 'anio', 'isPdf'));
    }

    public function descargarPDF(Request $request)
    {
        $empleadoId = $request->input('empleado_id');
        $tipoPermisoId = $request->input('tipo_permiso_id');
        $mes = $request->input('mes');
        $anio = $request->input('anio');

        // Repetir la lógica de filtrado para obtener los permisos
        $query = Permiso::query();

        if ($empleadoId && $empleadoId != 'todos') {
            $query->where('empleado_id', $empleadoId);
        }

        if ($tipoPermisoId && $tipoPermisoId != 'todos') {
            $query->where('tipo_permiso_id', $tipoPermisoId);
        }

        $query->whereYear('fecha_inicio', $anio);

        if ($mes && $mes != 'todos') {
            $query->whereMonth('fecha_inicio', $mes);
        }

        $permisos = $query->with('empleado')
            ->join('tipo_permisos', 'permisos.tipo_permiso_id', '=', 'tipo_permisos.id')
            ->select('permisos.*', 'tipo_permisos.nombre as tipo_permiso')
            ->orderBy('fecha_inicio')
            ->get();

        $empleado = $empleadoId && $empleadoId != 'todos' ? Empleado::findOrFail($empleadoId) : null;
        $tiposPermiso = TipoPermiso::all();

        // Generar el PDF con la nueva vista
        $pdf = PDF::loadView('admin.permisos.pdf_reporte', compact('permisos', 'empleado', 'mes', 'anio', 'empleadoId', 'tipoPermisoId', 'tiposPermiso'));

        // Descargar el PDF
        return $pdf->download('reporte_permisos.pdf');
    }

    public function listaSupervisor()
    {
        // Obtener los tipos de permisos que pueden ver los supervisores
        $tiposPermiso = TipoPermiso::all(); // Aquí puedes agregar condiciones si es necesario

        return view('supervisor.permisos.lista', compact('tiposPermiso'));
    }

    public function listaEmpleado()
    {
        // Obtener los tipos de permisos que pueden ver los empleados
        $tiposPermiso = TipoPermiso::all(); // Aquí también puedes agregar condiciones si es necesario

        return view('empleado.permisos.lista', compact('tiposPermiso'));
    }




}
