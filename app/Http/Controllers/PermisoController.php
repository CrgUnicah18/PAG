<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Empleado;
use App\Models\TipoPermiso;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PermisoController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        // Obtener el empleado asociado al usuario logueado
        $empleado = Empleado::find($user->empleado_id);

        // Obtener el estado y nombre del filtro si están presentes
        $estado = $request->input('estado');
        $nombreEmpleado = $request->nombreEmpleado;

        // Verificar si el empleado existe
        if (!$empleado) {
            return redirect()->back()->with('error', 'El empleado no existe en el sistema.');
        }

        // Inicializar las variables
        $permisosSupervisor = [];
        $permisosEmpleados = [];
        $empleadosBajoSupervision = [];  // Inicializamos la variable
        $permisosEmpleado = [];
        $permisosAdmin = [];

        // Obtener todos los empleados para el filtro
        $empleados = Empleado::all();

        // Obtener el usuario actual
        $empleado = $user->empleado;  // Suponiendo que cada usuario tiene un "empleado"

        // Validamos si el usuario es supervisor
        if ($user->hasRole('supervisor')) {
            // Obtener los empleados bajo la supervisión del supervisor
            $empleadosBajoSupervision = Empleado::where('supervisor_id', $empleado->id)->get();

            // Verificar si hay empleados bajo supervisión
            if ($empleadosBajoSupervision->isNotEmpty()) {
                // Obtener los permisos de los empleados bajo su supervisión con el filtro de nombreEmpleado
                $permisosEmpleados = Permiso::whereIn('empleado_id', $empleadosBajoSupervision->pluck('id'))
                    ->when($nombreEmpleado, function ($query, $nombreEmpleado) {
                        return $query->whereHas('empleado', function ($query) use ($nombreEmpleado) {
                            $query->where('nombre', 'like', '%' . $nombreEmpleado . '%');
                        });
                    })
                    ->when($estado, function ($query, $estado) {
                        return $query->where('estado', $estado);
                    })
                    ->paginate(10);
            } else {
                // Si no hay empleados bajo supervisión, inicializamos permisosEmpleados como vacío
                $permisosEmpleados = collect(); // o Permiso::whereIn('empleado_id', [])->paginate(10);
            }

            // Obtener los permisos del propio supervisor
            $permisosSupervisor = Permiso::where('empleado_id', $empleado->id)->paginate(10);

            // Retornamos la vista con los permisos del supervisor y de los empleados bajo su supervisión
            return view('supervisor.permisos.index', compact('empleadosBajoSupervision', 'permisosSupervisor', 'permisosEmpleados', 'empleados'));
        }

        // Si no es supervisor, mostrar solo los permisos de este empleado
        if ($user->hasRole('empleado')) {
            // Obtener los permisos del propio empleado
            $permisosEmpleado = Permiso::where('empleado_id', $empleado->id)->paginate(10);
            return view('empleado.permisos.index', compact('permisosEmpleado', 'empleados'));
        }

        if ($user->hasRole('admin')) {
            // Permisos del propio empleado (admin también es empleado)
            $permisosAdmin = Permiso::where('empleado_id', $user->empleado->id)->paginate(10);

            // Permisos filtrados por estado y nombre del empleado
            $permisos = Permiso::when($estado, function ($query, $estado) {
                return $query->where('estado', $estado);
            })
                ->when($nombreEmpleado, function ($query, $nombreEmpleado) {
                    return $query->whereHas('empleado', function ($q) use ($nombreEmpleado) {
                        $q->where('nombre', 'like', '%' . $nombreEmpleado . '%');
                    });
                })
                ->paginate(10);

            return view('admin.permisos.index', compact('permisos', 'permisosAdmin', 'empleados', 'nombreEmpleado', 'estado'));
        }

        // Si no es ni supervisor, ni empleado, ni admin
        return redirect()->back()->with('error', 'No tienes acceso a esta sección.');
    }





    // Mostrar el formulario para solicitar un permiso
    public function create()
    {
        $user = auth()->user(); // Obtener el usuario logueado
        $tiposPermiso = TipoPermiso::where('es_vacacion', 0); // Filtrar permisos que no sean vacaciones

        // Si el empleado es femenino (género "F"), incluir permisos de tipo "licencia"
        if ($user->genero === 'F') {
            $tiposPermiso = $tiposPermiso->orWhere('es_licencia', 1); // Agregar permisos de licencia solo si es femenino
        }

        // Obtener los tipos de permiso
        $tiposPermiso = $tiposPermiso->get();

        // Comprobar los roles del usuario y devolver la vista correspondiente
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
        $permisosEsteMes = Permiso::where('empleado_id', $empleado->id)
            ->whereYear('fecha_inicio', Carbon::now()->year) // Filtrar por el año actual
            ->whereMonth('fecha_inicio', Carbon::now()->month) // Filtrar por el mes actual
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





    // Aprobar un permiso (Supervisor o Admin)
    // Aprobar un permiso (Supervisor o Admin)
    public function aprobar($id)
    {
        $permiso = Permiso::findOrFail($id);
        $user = auth()->user();

        // El supervisor puede pre-aprobar, solo si el estado es 'pendiente'
        if ($user->hasRole('supervisor') && $permiso->estado == 'pendiente') {
            $permiso->update(['estado' => 'pendiente_aprobacion']);
            return redirect()->route('supervisor.permisos.index')->with('success', 'Pre-aprobado por supervisor.');
        }

        // El admin puede aprobar, solo si el estado es 'pendiente_aprobacion'
        if ($user->hasRole('admin') && $permiso->estado == 'pendiente_aprobacion' || $permiso->estado == 'pendiente') {
            // Evitar que el admin apruebe su propio permiso
            if ($permiso->empleado_id == $user->empleado->id) {
                return redirect()->back()->with('error', 'No puedes aprobar tu propio permiso.');
            }

            $permiso->update(['estado' => 'aprobado']);

            // Ahora que el permiso fue aprobado, cambiar el estado del empleado a 'inactivo'
            $empleado = $permiso->empleado;
            $empleado->update(['estado' => 'inactivo']); // Cambio el estado del empleado a 'inactivo'
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



}
