<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Empleado;
use App\Models\TipoPermiso;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\PDF;
use App\Notifications\SolicitudPermisosNotificacion;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use App\Events\MyEvent;
use App\Events\NotificacionCreada;

class PermisoController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $empleado = Empleado::find($user->empleado_id);

        // Obtener los filtros desde la solicitud
        $estado = $request->input('estado');
        $nombreEmpleado = $request->input('nombreEmpleado');

        // Guardar los filtros en la sesión para mantenerlos
        session([
            'estado' => $estado,
            'nombreEmpleado' => $nombreEmpleado,
        ]);

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
                        return $query->whereHas('empleado', function ($q) use ($nombreEmpleado) {
                            $q->where('nombre', 'like', '%' . $nombreEmpleado . '%')
                                ->orWhere('apellido', 'like', '%' . $nombreEmpleado . '%');
                        });
                    })
                    ->when($estado, function ($query, $estado) {
                        return $query->where('estado', $estado);
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(6);

                // Calcular los días laborables para cada permiso
                foreach ($permisosEmpleados as $permiso) {
                    $fechaInicio = Carbon::parse($permiso->fecha_inicio);
                    $fechaFin = Carbon::parse($permiso->fecha_fin);

                    $diasLaborables = 0;
                    $fechaAuxiliar = $fechaInicio->copy();

                    while ($fechaAuxiliar->lte($fechaFin)) {
                        if (!$fechaAuxiliar->isWeekend()) {
                            $diasLaborables++;
                        }
                        $fechaAuxiliar->addDay();
                    }

                    $permiso->dias_laborables = $diasLaborables; // Agregar el cálculo al objeto
                }
            } else {
                $permisosEmpleados = collect();
            }

            $permisosSupervisor = Permiso::where('empleado_id', $empleado->id)
                ->orderBy('created_at', 'desc')
                ->paginate(6);

            // Calcular los días laborables para permisos del supervisor
            foreach ($permisosSupervisor as $permiso) {
                $fechaInicio = Carbon::parse($permiso->fecha_inicio);
                $fechaFin = Carbon::parse($permiso->fecha_fin);

                $diasLaborables = 0;
                $fechaAuxiliar = $fechaInicio->copy();

                while ($fechaAuxiliar->lte($fechaFin)) {
                    if (!$fechaAuxiliar->isWeekend()) {
                        $diasLaborables++;
                    }
                    $fechaAuxiliar->addDay();
                }

                $permiso->dias_laborables = $diasLaborables; // Agregar el cálculo al objeto
            }

            return view('supervisor.permisos.index', compact('empleadosBajoSupervision', 'permisosSupervisor', 'permisosEmpleados', 'empleados'));
        }

        // EMPLEADO
        if ($user->hasRole('empleado')) {
            $permisosEmpleado = Permiso::where('empleado_id', $empleado->id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Calcular los días laborables para cada permiso
            foreach ($permisosEmpleado as $permiso) {
                $fechaInicio = Carbon::parse($permiso->fecha_inicio);
                $fechaFin = Carbon::parse($permiso->fecha_fin);

                $diasLaborables = 0;
                $fechaAuxiliar = $fechaInicio->copy();

                while ($fechaAuxiliar->lte($fechaFin)) {
                    if (!$fechaAuxiliar->isWeekend()) {
                        $diasLaborables++;
                    }
                    $fechaAuxiliar->addDay();
                }

                $permiso->dias_laborables = $diasLaborables; // Agregar el cálculo al objeto
            }

            return view('empleado.permisos.index', compact('permisosEmpleado'));
        }

        // ADMIN
        if ($user->hasRole('admin')) {
            $permisosAdmin = Permiso::where('empleado_id', $user->empleado->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $permisos = Permiso::when($estado, function ($query, $estado) {
                return $query->where('estado', $estado);
            })
                ->when($nombreEmpleado, function ($query, $nombreEmpleado) {
                    return $query->whereHas('empleado', function ($q) use ($nombreEmpleado) {
                        $q->where('nombre', 'like', '%' . $nombreEmpleado . '%');
                    });
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Calcular los días laborables para cada permiso
            foreach ($permisos as $permiso) {
                $fechaInicio = Carbon::parse($permiso->fecha_inicio);
                $fechaFin = Carbon::parse($permiso->fecha_fin);

                $diasLaborables = 0;
                $fechaAuxiliar = $fechaInicio->copy();

                while ($fechaAuxiliar->lte($fechaFin)) {
                    if (!$fechaAuxiliar->isWeekend()) {
                        $diasLaborables++;
                    }
                    $fechaAuxiliar->addDay();
                }

                $permiso->dias_laborables = $diasLaborables; // Agregar el cálculo al objeto
            }

            return view('admin.permisos.index', compact('permisosAdmin', 'permisos', 'empleados', 'nombreEmpleado', 'estado'));
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
        $currentYear = date('Y');

        // Validar los campos de la solicitud
        $request->validate([
            'tipo_permiso_id' => 'required|exists:tipo_permisos,id',
            'fecha_inicio' => 'required|date|after_or_equal:' . Carbon::today()->toDateString(),
            'fecha_fin' => 'date|after_or_equal:fecha_inicio',
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

        // Obtener el tipo de permiso seleccionado
        $tipoPermiso = TipoPermiso::find($request->tipo_permiso_id);

        // Verificar si el tipo de permiso requiere subsidio
        if ($tipoPermiso && $tipoPermiso->requiere_subsidio == 1) {
            // Validar que se haya subido un archivo (pdf o imagen)
            $request->validate([
                'subsidio_archivo' => 'nullable|mimes:pdf,jpg,jpeg,png|max:10240', // Máximo 10MB
            ]);
        }

        // ** Ignorar validación de 2 permisos por mes para subsidio o calamidad **
        if ($tipoPermiso && ($tipoPermiso->requiere_subsidio == 1 || $tipoPermiso->calamidad == 1)) {
            // No validamos el máximo de 2 permisos por mes para subsidio y calamidad
        } else {
            // ** Validación 1: Verificar el máximo de 2 solicitudes por mes para los demás permisos **
            $permisosEsteMes = Permiso::where('empleado_id', $empleado->id)
                ->whereYear('fecha_inicio', Carbon::now()->year)
                ->whereMonth('fecha_inicio', Carbon::now()->month)
                ->whereIn('estado', ['pendiente', 'pendiente_aprobacion', 'aprobado'])
                ->count();

            if ($permisosEsteMes >= 2) {
                return redirect()->back()->with('error', 'Ya has alcanzado el límite de 2 permisos por mes.');
            }
        }

        // ** Validación 2: Verificar el máximo de 15 permisos aprobados por año para todos los permisos **
        $permisosEsteAnioAprobados = Permiso::where('empleado_id', $empleado->id)
            ->whereYear('fecha_inicio', Carbon::now()->year)
            ->where('estado', 'aprobado') // Solo contar los permisos aprobados
            ->count();

        if ($permisosEsteAnioAprobados >= 15) {
            return redirect()->back()->with('error', 'Ya has alcanzado el límite de 15 permisos aprobados por año.');
        }

        // Si el tipo de permiso no es vacación, verificar duración
        if ($tipoPermiso && $tipoPermiso->es_vacacion == 0) {
            // Si fecha_fin está vacía, asignamos el mismo valor de fecha_inicio
            if (empty($request->fecha_fin)) {
                $request->merge(['fecha_fin' => $request->fecha_inicio]);
            }

            // Calcular la duración del permiso solo contando los días laborables
            $fechaInicio = Carbon::parse($request->fecha_inicio);
            $fechaFin = Carbon::parse($request->fecha_fin);

            $reintegro = $fechaFin->copy()->addDay();
            while ($reintegro->isWeekend()) {
                $reintegro->addDay();
            }

            $diasDuracion = 0;
            $fechaAuxiliar = $fechaInicio->copy();

            // Contamos los días laborables, excluyendo los fines de semana
            while ($fechaAuxiliar <= $fechaFin) {
                if (!$fechaAuxiliar->isWeekend()) {
                    $diasDuracion++;
                }
                $fechaAuxiliar->addDay();
            }

            // Ajustar la fecha de fin según la duración del permiso, considerando los días laborables
            $diasRestantes = $diasDuracion - 1; // Restamos 1 porque el día de inicio ya cuenta como un día laborable
            $fechaFinAjustada = $fechaInicio->copy();

            if ($diasDuracion == 1) {
                $fechaFinAjustada = $fechaInicio;
            } else {
                while ($diasRestantes > 0) {
                    $fechaFinAjustada->addDay();
                    if (!$fechaFinAjustada->isWeekend()) {
                        $diasRestantes--;
                    }
                }
            }

            // Actualizar la fecha de fin en la solicitud
            $request->merge(['fecha_fin' => $fechaFinAjustada->format('Y-m-d')]);

            /*dd([
                'fecha_inicio' => $fechaInicio->format('Y-m-d'),
                'fecha_fin' => $fechaFin->format('Y-m-d'),
                'dias_duracion' => $diasDuracion,
            ]);*/
        }

        //dd($request->fecha_inicio, $request->fecha_fin);

        // Verificar si hay permisos pendientes o pendientes_aprobacion con fechas sobrepuestas
        $fechasSobrepuestas = Permiso::where('empleado_id', $empleado->id)
            ->whereIn('estado', ['pendiente', 'pendiente_aprobacion', 'aprobado'])
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

        // Subir archivo de subsidio si se requiere
        $archivoSubsidio = null;
        if ($tipoPermiso && $tipoPermiso->requiere_subsidio == 1) {
            if ($request->hasFile('subsidio_archivo')) {
                // Obtener el archivo
                $archivo = $request->file('subsidio_archivo');
                // Limpiar y definir el nombre del archivo
                $nombreOriginal = $archivo->getClientOriginalName();
                $nombreLimpio = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $nombreOriginal); // Quita espacios raros
                $nombreArchivo = time() . '_' . $nombreLimpio;

                // Guardar el archivo en storage/app/public/subsidios
                $archivo->storeAs('subsidios', $nombreArchivo, 'public');
                $archivoSubsidio = $archivo->storeAs('subsidios', $nombreArchivo, 'public');
            }
        }
        // Crear el nuevo permiso
        $permiso = new Permiso();
        $permiso->empleado_id = $empleado->id;
        $permiso->tipo_permiso_id = $request->tipo_permiso_id;
        $permiso->fecha_inicio = $request->fecha_inicio;
        $permiso->fecha_fin = $request->fecha_fin;
        $permiso->estado = 'pendiente';
        $permiso->comentario = $request->comentario;
        $permiso->periodo = $currentYear;  // Asignamos el año actual al campo periodo
        $permiso->subsidio_archivo = $archivoSubsidio;
        $permiso->reintegro = $reintegro; // Asignar la fecha de reintegro
        $permiso->save();

        $mensaje = "Nueva solicitud de permiso de " . $empleado->nombre;
        $url = route('admin.permisos.index'); // URL a la que redirigir después de la notificación

        event(new MyEvent('hello world', "admin.permisos.index"));

        // Obtener los administradores
        $admins = User::role('admin')->get(); // Obtener todos los admins

        // Obtener el supervisor del empleado (si tiene)
        $supervisor = $empleado->supervisor ? $empleado->supervisor->user : null;

        // Combinar los destinatarios en una colección
        $destinatarios = $admins->concat($supervisor ? collect([$supervisor]) : collect())->unique('id');

        // Enviar la notificación a los destinatarios
        Notification::send($destinatarios, new SolicitudPermisosNotificacion($permiso));

        // Redirigir según el rol del usuario y agregar notificación flash para admins
        if ($user->hasRole('supervisor')) {
            session()->flash('notify', [
                ['type' => 'success', 'message' => '¡Permiso Creado!'],
            ]);
            return redirect()->route('supervisor.permisos.index')->with('success', 'Solicitud de permiso enviada.');
        }

        if ($user->hasRole('empleado')) {
            session()->flash('notify', [
                ['type' => 'success', 'message' => '¡Permiso Creado!'],
            ]);
            return redirect()->route('empleado.permisos.index')->with('success', 'Solicitud de permiso enviada.');
        }

        if ($user->hasRole('admin')) {
            session()->flash('notify', [
                ['type' => 'success', 'message' => '¡Nueva solicitud de permiso creada!'],
            ]);
            event(new NotificacionCreada($mensaje, $url)); // Aquí puede ser tu evento de notificación adicional
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
            ;

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

        // Buscar el permiso
        $permiso = Permiso::findOrFail($permisoId);

        // Obtener el nombre del usuario autenticado
        $usuario = auth()->user();

        // Verificar si el usuario tiene el rol 'admin' usando Spatie
        if ($usuario->hasRole('admin')) {
            $autor = 'Admin'; // Si el usuario tiene el rol 'admin', asignamos 'Admin'
        } else {
            $autor = 'Empleado'; // En caso contrario, 'Empleado'
        }

        // Armar el nuevo comentario con fecha y autor
        $nuevoComentario = "[" . now()->format('d/m/Y H:i') . " - {$autor}]: " . $request->comentario;

        // Si ya hay un comentario anterior, lo concatenamos con el nuevo
        if ($permiso->comentario) {
            // Evitar duplicación
            $comentarios = explode("\n\n", $permiso->comentario);
            if (!in_array($nuevoComentario, $comentarios)) {
                $permiso->comentario .= "\n\n" . $nuevoComentario;
            }
        } else {
            $permiso->comentario = $nuevoComentario;
        }

        // Guardar
        $permiso->save();

        // Redirigir con mensaje de éxito
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
        // Usar los parámetros con fallback por si no vienen
        $empleadoId = $request->input('empleado_id', 'todos');
        $tipoPermisoId = $request->input('tipo_permiso_id', 'todos');
        $mes = $request->input('mes', 'todos');
        $anio = $request->input('anio', date('Y'));
        $estado = $request->input('estado', ''); // Nuevo filtro para estado

        $query = Permiso::query();

        // Filtros de búsqueda
        if ($empleadoId && $empleadoId != 'todos') {
            $query->where('empleado_id', $empleadoId);
        }

        if ($tipoPermisoId && $tipoPermisoId != 'todos') {
            $query->where('tipo_permiso_id', $tipoPermisoId);
        }

        if ($estado) {
            $query->where('estado', $estado);  // Filtrar por estado
        }

        $query->whereYear('fecha_inicio', $anio);

        if ($mes && $mes != 'todos') {
            $query->whereMonth('fecha_inicio', $mes);
        }

        // Obtener los permisos sin paginación para agrupar
        $permisos = $query->with('empleado')
            ->join('tipo_permisos', 'permisos.tipo_permiso_id', '=', 'tipo_permisos.id')
            ->select('permisos.*', 'tipo_permisos.nombre as tipo_permiso')
            ->orderBy('fecha_inicio')
            ->get(); // Aquí usamos get() en vez de paginate() para obtener todos los permisos

        // Agrupar permisos por mes y año
        $permisosAgrupados = $permisos->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->fecha_inicio)->format('F Y'); // Agrupar por mes y año
        });

        // Calcular el total general de permisos
        $totalPermisos = $permisos->count();

        // Obtener el empleado si fue filtrado
        $empleado = $empleadoId && $empleadoId != 'todos' ? Empleado::findOrFail($empleadoId) : null;

        // Se indica que no es para PDF
        $isPdf = false;

        // Mostrar la vista del reporte
        return view('admin.permisos.reporte_pdf', compact('permisosAgrupados', 'empleado', 'empleadoId', 'tipoPermisoId', 'mes', 'anio', 'isPdf', 'permisos', 'totalPermisos', 'estado'));
    }

    public function descargarPDF(Request $request)
    {

        // Obtener los valores de los filtros
        $empleadoId = $request->input('empleado_id');
        $tipoPermisoId = $request->input('tipo_permiso_id');
        $mes = $request->input('mes');
        $anio = $request->input('anio');
        $estado = $request->input('estado');

        // Repetir la lógica de filtrado para obtener los permisos
        $query = Permiso::query();

        // Filtros aplicados para cada uno
        if ($empleadoId && $empleadoId != 'todos') {
            $query->where('empleado_id', $empleadoId);
        }

        if ($tipoPermisoId && $tipoPermisoId != 'todos') {
            $query->where('tipo_permiso_id', $tipoPermisoId);
        }

        if ($estado) {  // Solo aplicamos filtro si no es nulo
            $query->where('estado', $estado);
        }

        $query->whereYear('fecha_inicio', $anio);  // Filtrado por año

        if ($mes && $mes != 'todos') {
            $query->whereMonth('fecha_inicio', $mes);  // Filtrado por mes
        }

        // Obtener los permisos que cumplen con los filtros
        $permisos = $query->with('empleado')
            ->join('tipo_permisos', 'permisos.tipo_permiso_id', '=', 'tipo_permisos.id')
            ->select('permisos.*', 'tipo_permisos.nombre as tipo_permiso')
            ->orderBy('fecha_inicio')
            ->get();

        // Agrupar permisos por mes y año
        $permisosAgrupados = $permisos->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->fecha_inicio)->format('F Y'); // Agrupar por mes y año
        });

        // Obtener la información del empleado si fue filtrado
        $empleado = $empleadoId && $empleadoId != 'todos' ? Empleado::findOrFail($empleadoId) : null;

        // Obtener todos los tipos de permisos
        $tiposPermiso = TipoPermiso::all();

        // Calcular el total general de permisos
        $totalPermisos = $permisos->count();

        // Generar el PDF con la vista
        $pdf = PDF::loadView('admin.permisos.pdf_reporte', compact('permisosAgrupados', 'empleado', 'mes', 'anio', 'empleadoId', 'tipoPermisoId', 'tiposPermiso', 'estado', 'totalPermisos'));

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

    public function calcularDias($permiso)
    {
        return Carbon::parse($permiso->fecha_inicio)->diffInDays(Carbon::parse($permiso->fecha_fin)) + 1;
    }

    public function generarFormatoPermiso($permisoId)
    {
        // Obtener el permiso específico por su ID
        $permiso = Permiso::findOrFail($permisoId);
        // Obtener el tipo de permiso relacionado
        $tipoPermiso = $permiso->tipoPermiso;

        // Comprobar si el permiso está aprobado
        if ($permiso->estado !== 'aprobado') {
            return redirect()->back()->with('error', 'Este permiso no ha sido aprobado aún.');
        }

        // Calcular los días laborables para este permiso
        $fechaInicio = Carbon::parse($permiso->fecha_inicio);
        $fechaFin = Carbon::parse($permiso->fecha_fin);

        $diasLaborables = 0;
        $fechaAuxiliar = $fechaInicio->copy();

        while ($fechaAuxiliar->lte($fechaFin)) {
            if (!$fechaAuxiliar->isWeekend()) {
                $diasLaborables++;
            }
            $fechaAuxiliar->addDay();
        }

        $permiso->dias_laborables = $diasLaborables; // Asignar el valor calculado a la propiedad del permiso
        $permiso->periodo = Carbon::now()->year; // Asignar el año actual al campo periodo

        $pdf = Pdf::loadView('admin.permisos.permiso_formato', compact('permiso', 'tipoPermiso'));

        return $pdf->download('Solicitud de Permiso ' . $permiso->empleado->nombre . " " . $permiso->empleado->apellido . '.pdf');
    }

}
