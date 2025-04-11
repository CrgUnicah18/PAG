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
use App\Notifications\SolicitudPermisoNotificacion;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EstadoSolicitudNotificacion;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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

        // Obtener el total de días de vacaciones a los que tiene derecho el empleado
        $diasTotales = $empleado->calcularBalanceVacaciones();

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
                ->orderBy('created_at', 'desc')
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
                ->orderBy('created_at', 'desc')
                ->paginate(8)
                ->appends(['nombreEmpleado' => $nombreEmpleado, 'estado' => $estado]); // Agregar filtros a la paginación

            return view('admin.vacaciones.index', compact('vacacionesPropias', 'vacacionesGenerales', 'empleados', 'tiposVacaciones', 'vacacionesRestantes', 'diasTotales', 'nombreEmpleado', 'estado'));
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
                ->orderBy('created_at', 'desc')
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
                ->orderBy('created_at', 'desc') // Ordenar por fecha de creación
                ->paginate(8)
                ->appends(['nombreEmpleado' => $nombreEmpleado, 'estado' => $estado]); // Agregar filtros a la paginación

            return view('supervisor.vacaciones.index', compact('vacacionesPropias', 'vacacionesGenerales', 'empleados', 'tiposVacaciones', 'vacacionesRestantes', 'diasTotales', 'nombreEmpleado', 'estado'));
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
                ->orderBy('created_at', 'desc')
                ->paginate(8)
                ->appends(['nombreEmpleado' => $nombreEmpleado, 'estado' => $estado]); // Agregar filtros a la paginación

            // No es necesario mostrar todas las solicitudes para un empleado
            $vacacionesGenerales = collect(); // Solo mostramos las propias del empleado

            return view('empleado.vacaciones.index', compact('vacacionesPropias', 'vacacionesGenerales', 'empleados', 'tiposVacaciones', 'vacacionesRestantes', 'diasTotales', 'nombreEmpleado', 'estado'));
        }

        // Si no tiene un rol válido
        return abort(403, 'No tienes permiso para ver esta página');



    }



    public function store(Request $request)
    {
        $currentYear = date('Y');
        // Validación común para todos los tipos de solicitud de vacaciones
        $validated = $request->validate(
            [
                'empleado_id' => 'required|exists:empleados,id',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date',
                'tipo_permiso_id' => 'required|exists:tipo_permisos,id', // Asegúrate que este campo esté bien definido
            ],
            [
                'fecha_inicio.after_or_equal' => 'La fecha de inicio debe ser igual o posterior a hoy.',
                'fecha_fin.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
                'fecha_fin.after' => 'La fecha de fin debe ser posterior a hoy.',
                'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            ]
        );

        //dd($validated);  // Verifica los datos validados

        $user = auth()->user();
        $empleadoId = $user->empleado_id;

        $solicitudesExistentes = Vacacion::where('empleado_id', $empleadoId)
            ->whereNotIn('estado', ['rechazadas']) // Excluir las solicitudes rechazadas
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
            return redirect()->back()->withErrors(['error_key' => 'Ya existe una solicitud de vacaciones con fechas sobrepuestas.']);
        }

        $fechaInicio = Carbon::parse($request->fecha_inicio);
        $fechaFin = Carbon::parse($request->fecha_fin);

        // Si la fecha de fin está vacía, asignamos la fecha de inicio
        if (empty($request->fecha_fin)) {
            $request->merge(['fecha_fin' => $request->fecha_inicio]);
        }
        // Cálculo del día de reintegro automático
        $reintegro = $fechaFin->copy()->addDay();
        while ($reintegro->isWeekend()) {
            $reintegro->addDay();
        }

        // Calcular la duración de las vacaciones solo contando los días laborables
        $duracionDias = 0;
        $fechaAuxiliar = $fechaInicio->copy();

        // Contamos los días laborables, excluyendo los fines de semana
        while ($fechaAuxiliar <= $fechaFin) {
            if (!$fechaAuxiliar->isWeekend()) {
                $duracionDias++;
            }
            $fechaAuxiliar->addDay();
        }

        // Aquí puedes verificar si la duración está dentro de los límites de días de vacaciones permitidos (si aplicable)
        $request->merge(['duracion' => $duracionDias]);

        // Actualizamos la fecha de fin en la solicitud si es necesario (aunque solo la duración es lo importante)
        $request->merge(['fecha_fin' => $fechaFin->format('Y-m-d')]);

        // Ahora puedes hacer la validación adicional que necesites para asegurarte que la duración no exceda el máximo de vacaciones permitidas


        // Si la duración de días es 0, significa que no se eligieron días laborables
        if ($duracionDias <= 0) {
            return redirect()->back()->withErrors(['error_key' => 'No se seleccionaron días laborables válidos.']);
        }

        // dd($duracionDias); // Verifica la cantidad de días laborables calculados

        // dd($duracionDias); // Verifica la cantidad de días laborables calculados


        //dd($duracionDias);

        // Obtener el empleado y calcular sus vacaciones restantes
        $empleado = Empleado::find($empleadoId);
        $vacacionesRestantes = $empleado->calcularBalanceVacaciones(); // Llamada al método de cálculo

        //dd($empleado, $vacacionesRestantes);
        // Verificar si el empleado tiene suficientes vacaciones disponibles
        if ($vacacionesRestantes < $duracionDias) {
            return redirect()->back()->withErrors(['error_key' => 'No hay suficientes días de vacaciones disponibles.']);

        }
        //dd($vacacionesRestantes, $duracionDias);
        // Aquí muestro todo lo que está siendo actualizado
        //dd($request->all());

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

                // Verificamos si las fechas de inicio y fin son correctas antes del ciclo
                $fechaInicio = Carbon::parse($request->fecha_inicio);
                $fechaFin = Carbon::parse($request->fecha_fin);

                // Ahora, no modificamos las fechas que el usuario ingresó.
                while ($diasSolicitados > 0) {
                    // Aseguramos que no sobrepasemos el máximo de días permitidos
                    $diasParaSolicitar = min($diasSolicitados, $maxDiasPermiso);

                    // No hay necesidad de hacer más cambios aquí. Solo sumamos los días solicitados y calculamos el fin.
                    $vacacion = Vacacion::create([
                        'empleado_id' => $empleadoId,
                        'fecha_inicio' => $fechaInicio->toDateString(),
                        'fecha_fin' => $fechaFin->toDateString(),
                        'duracion_dias' => $diasParaSolicitar,
                        'estado' => 'pendiente',
                        'tipo_permiso_id' => $request->tipo_permiso_id,
                        'comentario' => $request->comentario,
                        'periodo' => $request->periodo,
                        'reintegro' => $reintegro->toDateString(),
                    ]);
                    // Antes de actualizar las fechas y días restantes, realizamos un debugging

                    $diasSolicitados -= $diasParaSolicitar;  // Restamos los días solicitados

                    /* Depuración después de actualizar las fechas y días restantes
                    dd([
                        'nuevaFechaInicio' => $fechaInicio->toDateString(),
                        'fechaFin' => $fechaFin->toDateString(),
                        'diasSolicitadosRestantes' => $diasSolicitados
                    ]);*/

                }

                // Descontar los días de vacaciones
                $empleado->vacaciones_restantes -= $duracionDias;
                $empleado->save();

                // Notificación al empleado
                $empleadoUser = $vacacion->empleado->user;
                if (!$empleadoUser->notifications()->where('data->link', $vacacion->id)->exists()) {
                    $empleadoUser->notify(new SolicitudPermisoNotificacion($vacacion));
                }

                // Notificar a los administradores
                $admins = User::role('admin')->get();
                foreach ($admins as $admin) {
                    if (!$admin->notifications()->where('data->link', $vacacion->id)->exists()) {
                        // Notificar en cola
                        Notification::send($admins, (new SolicitudPermisoNotificacion($vacacion))->delay(now()->addSeconds(2)));

                    }
                }

                // Retornar mensaje de éxito
                return redirect()->route('admin.vacaciones.index')->with('success', 'Solicitud de vacaciones propia creada correctamente.');
            } else {
                // Vacaciones de otro empleado
                $diasSolicitados = $duracionDias;
                while ($diasSolicitados > 0) {
                    $diasParaSolicitar = min($diasSolicitados, $maxDiasPermiso);

                    // Crear la solicitud de vacaciones parcial
                    $vacacion = Vacacion::create([
                        'empleado_id' => $request->empleado_id,
                        'fecha_inicio' => $fechaInicio->toDateString(),
                        'fecha_fin' => $fechaFin->toDateString(),
                        'duracion_dias' => $diasParaSolicitar,
                        'estado' => 'aprobadas',
                        'tipo_permiso_id' => $request->tipo_permiso_id,
                        'comentario' => $request->comentario,
                        'periodo' => $request->periodo,
                        'reintegro' => $reintegro->toDateString(),
                    ]);

                    // Actualizar las fechas y días restantes
                    $diasSolicitados -= $diasParaSolicitar;
                }

                // Verificar si la fecha de inicio de la solicitud es igual a la fecha actual
                $fechaActual = Carbon::now()->toDateString(); // Obtener la fecha actual del sistema

                if ($vacacion->fecha_inicio == $fechaActual) {
                    // Actualizar estado de empleado a inactivo
                    $empleado = Empleado::find($request->empleado_id);
                    $empleado->estado = 'inactivo'; // El empleado pasa a estar inactivo durante sus vacaciones
                    $empleado->save();
                }

                $empleado = Empleado::find($empleadoId); // o el empleado correspondient

                // Enviar notificación a todos los administradores
                $empleadoUser = $vacacion->empleado->user;
                if (!$empleadoUser->notifications()->where('data->link', $vacacion->id)->exists()) {
                    $empleadoUser->notify(new EstadoSolicitudNotificacion($vacacion, 'aprobadas'));
                }

                $admins = User::role('admin')->get();
                foreach ($admins as $admin) {
                    if (!$admin->notifications()->where('data->link', $vacacion->id)->exists()) {
                        // Notificar en cola
                        Notification::send($admins, (new SolicitudPermisoNotificacion($vacacion))->delay(now()->addSeconds(2)));

                    }
                }


                return redirect()->route('admin.vacaciones.index')->with('success', 'Solicitud de vacaciones para el empleado creada correctamente.');
            }
        }
        // Si el usuario es supervisor
        if ($user->hasRole('supervisor')) {
            $diasSolicitados = $duracionDias;
            $vacacion = null; // Inicializar la variable vacacion

            while ($diasSolicitados > 0) {
                $diasParaSolicitar = min($diasSolicitados, $maxDiasPermiso);

                // Crear la solicitud de vacaciones parcial con el estado 'pendiente'
                $vacacion = Vacacion::create([
                    'empleado_id' => $request->empleado_id,
                    'fecha_inicio' => $fechaInicio->toDateString(),
                    'fecha_fin' => $fechaFin->toDateString(),
                    'duracion_dias' => $diasParaSolicitar,
                    'estado' => 'pendiente', // Estado pendiente para que no pase a aprobado
                    'tipo_permiso_id' => $request->tipo_permiso_id,
                    'comentario' => $request->comentario,
                    'periodo' => $request->periodo,
                    'reintegro' => $reintegro->toDateString(),
                ]);

                // Actualizar las fechas y días restantes
                $diasSolicitados -= $diasParaSolicitar;
            }

            // Restamos los días de vacaciones disponibles
            $empleado = Empleado::find($request->empleado_id);
            $empleado->vacaciones_restantes -= $duracionDias;
            $empleado->save();

            if ($vacacion) {
                // Ahora que vacacion está definida, enviamos la notificación
                $vacacion->empleado->user->notify(new SolicitudPermisoNotificacion($vacacion));
                // Enviar notificación a todos los administradores
                $admins = User::role('admin')->get(); // Obtener todos los admins
                foreach ($admins as $admin) {
                    // Notificar en cola
                    Notification::send($admins, (new SolicitudPermisoNotificacion($vacacion))->delay(now()->addSeconds(2)));

                }
            }

            return redirect()->route('supervisor.vacaciones.index')->with('success', 'Solicitud de vacaciones creada para el empleado.');
        }

        // Si el usuario es empleado
        if ($user->hasRole('empleado')) {
            $diasSolicitados = $duracionDias;
            while ($diasSolicitados > 0) {
                $diasParaSolicitar = min($diasSolicitados, $maxDiasPermiso);

                // Crear la solicitud de vacaciones parcial
                $vacacion = Vacacion::create([
                    'empleado_id' => $empleadoId,
                    'fecha_inicio' => $fechaInicio->toDateString(),
                    'fecha_fin' => $fechaFin->toDateString(),
                    'duracion_dias' => $diasParaSolicitar,
                    'estado' => 'pendiente',
                    'tipo_permiso_id' => $request->tipo_permiso_id,
                    'comentario' => $request->comentario,
                    'periodo' => $request->periodo,
                    'reintegro' => $reintegro->toDateString(),
                ]);

                // Actualizar las fechas y días restantes
                $diasSolicitados -= $diasParaSolicitar;
            }

            // Restamos los días de vacaciones porque está en estado pendiente
            $empleado->vacaciones_restantes -= $duracionDias;
            $empleado->save();
            $vacacion->empleado->user->notify(new SolicitudPermisoNotificacion($vacacion));

            // Enviar notificación a todos los administradores
            $admins = User::role('admin')->get(); // Obtener todos los admins
            foreach ($admins as $admin) {
                // Notificar en cola
                Notification::send($admins, (new SolicitudPermisoNotificacion($vacacion))->delay(now()->addSeconds(2)));

            }

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

                })
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);

                // Notificar al usuario sobre el estado de la solicitud
                $vacacion->empleado->user->notify(new EstadoSolicitudNotificacion($vacacion, 'pendientes_aprobacion'));

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

            // Obtener el empleado y verificar si la fecha de inicio de la vacación es igual a la fecha actual
            $empleado = Empleado::find($vacacion->empleado_id);

            if ($vacacion->fecha_inicio == now()->toDateString()) { // Compara solo la fecha (sin hora)
                // Cambiar el estado del empleado a inactivo si la fecha de inicio es la misma que la del sistema
                $empleado->estado = 'inactivo';
                $empleado->save();
            }

            // Marcar como leída la notificación relacionada con la vacación
            $vacacion->empleado->user->notifications->where('data.id_vacacion', $vacacion->id)->markAsRead();

            // Notificar al usuario sobre el estado de la solicitud
            $vacacion->empleado->user->notify(new EstadoSolicitudNotificacion($vacacion, 'aprobadas'));


            // Ahora calculamos el balance de vacaciones
            $balanceVacaciones = $empleado->calcularBalanceVacaciones(); // Retorna un array

            // Extraemos los valores del array
            $vacacionesRestantes = $balanceVacaciones['vacaciones_restantes'] ?? 0;
            $vacacionesTomadas = $balanceVacaciones['vacaciones_tomadas'] ?? 0;

            // Actualizamos el saldo de vacaciones en el empleado
            $empleado->vacaciones_restantes = $vacacionesRestantes;
            $empleado->vacaciones_tomadas = $vacacionesTomadas;
            $empleado->save();

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
                    // Notificar al empleado sobre el estado de la solicitud
                    $vacacion->empleado->user->notify(new EstadoSolicitudNotificacion($vacacion, 'rechazadas'));

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

                // Notificar al empleado
                $vacacion->empleado->user->notifications->where('data.id_vacacion', $vacacion->id)->markAsRead();

                // Notificar al empleado sobre el estado de la solicitud
                $vacacion->empleado->user->notify(new EstadoSolicitudNotificacion($vacacion, 'rechazadas'));
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
        $query = Vacacion::with('empleado'); // Cargar la relación con Empleado

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
            $query->whereIn('estado', $request->estado);
        }

        // Obtener los resultados de las vacaciones con paginación
        $vacaciones = $query->paginate(10);

        // Concatenar el nombre completo del empleado
        $vacaciones->map(function ($vacacion) {
            $vacacion->empleado->nombre_completo = $vacacion->empleado->nombre . ' ' . $vacacion->empleado->apellido;
            return $vacacion;
        });

        // Campos seleccionados para mostrar en el reporte
        $camposSeleccionados = $request->input('campos', [
            'empleado_id',
            'fecha_inicio',
            'fecha_fin',
            'duracion_dias',
            'estado',
            'tipo_permiso_id',
            'comentario',
            'vacaciones_restantes', // Campo de la tabla Empleado
            'vacaciones_tomadas',   // Campo de la tabla Empleado
            'periodo', // Campo de la tabla Vacacion
        ]);

        // Pasar los resultados a la vista
        return view('admin.vacaciones.reporte', compact('vacaciones', 'camposSeleccionados'))
            ->with('filtros', $request->all()); // Pasamos los filtros a la vista
    }

    public function exportarPDF(Request $request)
    {
        $query = Vacacion::with('empleado'); // Cargar la relación con Empleado

        $empleadoSeleccionado = null;
        if ($request->filled('empleado_id')) {
            $empleadoSeleccionado = Empleado::find($request->empleado_id);
            $query->where('empleado_id', $request->empleado_id);
        }

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_fin]);
        }

        if ($request->filled('estado')) {
            $query->whereIn('estado', $request->estado);
        }

        // Obtener las vacaciones filtradas
        $vacaciones = $query->get();

        // Concatenar el nombre completo del empleado
        $vacaciones->map(function ($vacacion) {
            $vacacion->empleado->nombre_completo = $vacacion->empleado->nombre . ' ' . $vacacion->empleado->apellido;
            return $vacacion;
        });

        // Obtener los campos seleccionados, o usar valores predeterminados
        $camposSeleccionados = $request->input('campos', [
            'empleado_id',
            'fecha_inicio',
            'fecha_fin',
            'duracion_dias',
            'estado',
            'tipo_permiso_id',
            'comentario',
            'vacaciones_restantes', // Campo de la tabla Empleado
            'vacaciones_tomadas',   // Campo de la tabla Empleado
            'periodo', // Campo de la tabla Vacacion
        ]);

        // Generar el PDF usando la vista exclusiva con los campos seleccionados
        $pdf = PDF::loadView('admin.vacaciones.reporte_pdf', [
            'vacaciones' => $vacaciones,
            'camposSeleccionados' => $camposSeleccionados,
            'empleadoSeleccionado' => $empleadoSeleccionado,
        ]);

        // Descargar el PDF
        return $pdf->download('reporte_vacaciones.pdf');
    }


    public function exportarExcel(Request $request)
    {
        // Concatenar el nombre completo del empleado en la clase VacacionesExport
        return Excel::download(new VacacionesExport($request), 'reporte_vacaciones.xlsx');
    }
    public function generarFormatoVacacion($vacacionId)
    {
        // Obtener la vacación específica por su ID, incluyendo la relación con el empleado
        $vacacion = Vacacion::with('empleado')->findOrFail($vacacionId);

        // Obtener el tipo de permiso relacionado
        $tipoPermiso = $vacacion->tipoPermiso;

        // Calcular los días laborables para este permiso
        $fechaInicio = Carbon::parse($vacacion->fecha_inicio);
        $fechaFin = Carbon::parse($vacacion->fecha_fin);
        $vacacion->periodo = Carbon::now()->year; // Asignar el año actual al campo periodo
        $fechaRaw = $vacacion->empleado->fecha_ingreso;
        $fechaIngreso = date('d/m/Y', strtotime($fechaRaw));

        $pdf = Pdf::loadView('admin.vacaciones.vacacion_formato', compact('vacacion', 'tipoPermiso', 'fechaIngreso'));
        //dd($fechaIngreso);

        return $pdf->download('Solicitud de vacaciones ' . $vacacion->empleado->nombre . " " . $vacacion->empleado->apellido . '.pdf');
    }





}
