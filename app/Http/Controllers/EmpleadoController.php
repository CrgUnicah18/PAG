<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Oficina;
use App\Models\Grupo;
use App\Models\TipoContrato;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\VacacionesAsignadas;
use App\Exports\EmpleadosExportExcel;
use Maatwebsite\Excel\Facades\Excel;


class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user(); // Usuario autenticado
        $query = Empleado::query()->with(['user.roles', 'supervisor']); // Cargar relaciones necesarias

        // Aplicar filtros solo si existen en la solicitud
        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->filled('grupo_id')) {
            $query->where('grupo_id', $request->grupo_id);
        }

        if ($request->filled('oficina_id')) {
            $query->where('oficina_id', $request->oficina_id);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Si el usuario es supervisor, solo ve sus empleados asignados
        if ($user->hasRole('supervisor')) {
            $query->where('supervisor_id', $user->empleado_id);
        }

        // Paginación: 10 empleados por página
        $empleados = $query->paginate(10); // Cambié get() por paginate(10)

        // Obtener datos adicionales
        $grupos = Grupo::all();
        $oficinas = Oficina::all();
        $tiposContratos = TipoContrato::all();

        // Verifica el rol y retorna la vista correcta
        if ($user->hasRole('supervisor')) {
            return view('supervisor.empleados.index', compact('empleados', 'grupos', 'oficinas', 'tiposContratos'));
        } else {
            return view('admin.empleados.index', compact('empleados', 'grupos', 'oficinas', 'tiposContratos'));
        }
    }



    public function create()
    {
        $supervisores = Empleado::whereNull('supervisor_id')->get();
        $oficinas = Oficina::all();
        $grupos = Grupo::all();
        $tiposContratos = TipoContrato::all(); // Obtener todos los tipos de contrato

        return view('admin.empleados.create', [
            'oficinas' => $oficinas,
            'grupos' => $grupos,
            'supervisores' => $supervisores,
            'tiposContratos' => $tiposContratos, // Pasar los tipos de contrato a la vista
        ]);
    }

    public function show($id)
    {
        $user = auth()->user(); // Obtiene el usuario autenticado
        $empleado = Empleado::with('user')->findOrFail($id); // Carga la relación 'user'
        $oficinas = Oficina::all();
        $grupos = Grupo::all();
        $tiposContratos = TipoContrato::all();

        // Si es supervisor, no pasa el contrato a la vista
        if ($user->hasRole('supervisor')) {
            return view('supervisor.empleados.show', compact('empleado', 'oficinas', 'grupos', 'tiposContratos'));
        } else {
            return view('admin.empleados.show', compact('empleado', 'oficinas', 'grupos', 'tiposContratos'));
        }
    }


    public function storeEmpleado(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'fecha_nacimiento' => 'required|date',
            'oficina_id' => 'required|exists:oficinas,id',
            'grupo_id' => 'required|exists:grupos,id',
            'supervisor_id' => 'nullable|exists:empleados,id',
            'tipo_contrato_id' => 'required|exists:tipo_contratos,id',
            'fecha_ingreso' => 'required|date|before_or_equal:' . Carbon::now()->toDateString(),
            'estado' => 'required|in:activo,inactivo',
            'genero' => 'required|in:M,F',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'documento_contrato' => 'nullable|mimes:pdf,doc,docx|max:2048',
            'dn' => 'required|string|max:15',
            'dn_file' => 'required|mimes:pdf,jpeg,jpg,png|max:2048',
            'cargo' => 'required|string|max:50',
        ], [
            'fecha_ingreso.before_or_equal' => 'La fecha de ingreso no puede ser en el futuro.',
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser uno de los siguientes: activo, inactivo.',
            'foto_perfil.image' => 'La foto de perfil debe ser una imagen válida.',
            'foto_perfil.mimes' => 'La foto de perfil debe tener uno de los siguientes formatos: jpeg, png, jpg, gif.',
            'documento_contrato.mimes' => 'El documento del contrato debe ser un archivo PDF, DOC o DOCX.',
            'dn.required' => 'El DNI es obligatorio.',
            'dn.max' => 'El DNI no puede tener más de 15 caracteres.',
            'dn_file.required' => 'La fotografia del DNI es obligatorio.',
            'dn_file.mimes' => 'El archivo del DNI debe ser un archivo PDF, JPEG, JPG o PNG.',
            'cargo.required' => 'El cargo es obligatorio.',
            'cargo.max' => 'El cargo no puede tener más de 50 caracteres.',
        ]);

        // Inicia la transacción
        DB::beginTransaction();

        try {
            // Obtener el nombre del tipo de contrato
            $tipoContrato = TipoContrato::find($request->tipo_contrato_id);

            // Crear el empleado
            $empleado = new Empleado();
            $empleado->nombre = $request->nombre;
            $empleado->apellido = $request->apellido;
            $empleado->direccion = $request->direccion;
            $empleado->telefono = $request->telefono;
            $empleado->fecha_nacimiento = $request->fecha_nacimiento;
            $empleado->oficina_id = $request->oficina_id;
            $empleado->grupo_id = $request->grupo_id;
            $empleado->supervisor_id = $request->supervisor_id;
            $empleado->tipo_contrato_id = $request->tipo_contrato_id;
            $empleado->tipo_contrato = $tipoContrato->nombre; // Guardar el nombre del tipo de contrato
            $empleado->fecha_ingreso = $request->fecha_ingreso;
            $empleado->estado = $request->estado;
            $empleado->genero = $request->genero;
            $empleado->dn = $request->dn;
            $empleado->dn_file = $request->dn_file;
            $empleado->cargo = $request->cargo; // Guardar el cargo

            // Subir foto de perfil
            if ($request->hasFile('foto_perfil')) {
                $imageName = time() . '-' . $request->file('foto_perfil')->getClientOriginalName();
                $path = public_path('empleados/img');
                $request->file('foto_perfil')->move($path, $imageName);
                $empleado->foto_perfil = 'empleados/img/' . $imageName;
            }

            // Subir contrato
            if ($request->hasFile('documento_contrato')) {
                $documentName = time() . '-' . $request->file('documento_contrato')->getClientOriginalName();
                $path = public_path('empleados/img_contratos');
                $request->file('documento_contrato')->move($path, $documentName);
                $empleado->documento_contrato = 'empleados/img_contratos/' . $documentName;
            }

            // Subir dni
            if ($request->hasFile('dn_file')) {
                $imageName = time() . '-' . $request->file('dn_file')->getClientOriginalName();
                $path = public_path('empleados/dni');
                $request->file('dn_file')->move($path, $imageName);
                $empleado->dn_file = 'empleados/dni/' . $imageName;
            }

            // Guardar empleado
            $empleado->save();

            // Calcular y guardar el balance de vacaciones
            $balanceVacaciones = $empleado->calcularBalanceVacaciones();
            $empleado->vacaciones_restantes = $balanceVacaciones['vacaciones_restantes'];  // Asegúrate de usar el valor correcto
            $empleado->vacaciones_tomadas = $balanceVacaciones['vacaciones_tomadas'];  // Si lo necesitas
            $empleado->save();

            // Confirmar la transacción
            DB::commit();

            // Redirigir al formulario para crear el usuario, pasando el ID del empleado recién creado
            return redirect()->route('admin.createUsuario', ['empleado_id' => $empleado->id]);

        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();

            // Volver atrás y mostrar el mensaje de error
            return back()->withErrors(['error' => 'Ocurrió un error al guardar el empleado: ' . $e->getMessage()]);
        }
    }


    // Función para mostrar el formulario de creación de usuario para el empleado específico
    public function createUsuario($empleado_id)
    {
        // Obtener el empleado por su ID
        $empleado = Empleado::findOrFail($empleado_id);

        // Verificar si el empleado ya tiene un usuario
        if ($empleado->user) {
            return redirect()->route('admin.empleados.index')->with('error', 'Este empleado ya tiene un usuario asociado.');
        }

        // Mostrar el formulario de creación de usuario, pasando el empleado
        return view('admin.createUsuario', compact('empleado', 'empleado_id'));
    }

    // Función para almacenar el usuario
    public function storeUsuario(Request $request, $empleado_id)
    {
        // Validar los datos del formulario
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'name' => 'required|string', // Asegurarse de que el campo "name" sea obligatorio
            'rol' => 'required|in:admin,supervisor,empleado' // Validamos que el rol sea válido
        ]);

        // Crear el usuario con el ID del empleado
        $user = User::create([
            'empleado_id' => $request->empleado_id, // Asociar al empleado
            'email' => $request->email,
            'password' => bcrypt($request->password), // Encriptar la contraseña
            'name' => $request->name, // Usar el nombre ingresado manualmente
            'email_verified_at' => now(), // Establecer la fecha de verificación del email al momento de crear el usuario
            'rememberToken' => Str::random(60), // Generar un token de recordatorio de sesión
        ]);
        // Asignar rol seleccionado
        $user->assignRole($request->rol);

        // Redirigir a la vista de empleados con un mensaje de éxito
        return redirect()->route('admin.empleados.index')->with('success', 'Empleado creado correctamente con rol: ' . $request->rol);
    }

    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        $oficinas = Oficina::all();
        $grupos = Grupo::all();
        $supervisores = Empleado::whereNull('supervisor_id')->get();
        $tiposContratos = TipoContrato::all();

        return view('admin.empleados.edit', compact('empleado', 'oficinas', 'grupos', 'supervisores', 'tiposContratos'));
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        // Validación de los datos del formulario
        $request->validate(
            [
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'genero' => 'required|in:M,F',
                'telefono' => 'required|string|max:255',
                'grupo_id' => 'required|exists:grupos,id',
                'oficina_id' => 'required|exists:oficinas,id',
                'supervisor_id' => 'nullable|exists:empleados,id',
                'estado' => 'required|in:activo,inactivo',
                'tipo_contrato_id' => 'required|exists:tipo_contratos,id',
                'fecha_ingreso' => 'required|date',
                'foto_peril' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'documento_contrato' => 'nullable|mimes:pdf,doc,docx,zip|max:2048',
                'dn' => 'required|string|max:15|unique:empleados,dn,' . $empleado->id,
                'dn_file' => 'nullable|mimes:pdf,jpeg,jpg,png|max:2048', // Para almacenar el archivo (fotografía o PDF)
                'cargo' => 'required|string|max:50',
            ]
        );

        // Actualización de los datos del empleado
        $empleado->nombre = $request->nombre;
        $empleado->apellido = $request->apellido;
        $empleado->telefono = $request->telefono;
        $empleado->grupo_id = $request->grupo_id;
        $empleado->oficina_id = $request->oficina_id;
        $empleado->supervisor_id = $request->supervisor_id;
        $empleado->estado = $request->estado;
        $empleado->tipo_contrato_id = $request->tipo_contrato_id;
        $empleado->fecha_ingreso = $request->fecha_ingreso;
        $empleado->genero = $request->genero;
        $empleado->dn = $request->dn;
        $empleado->cargo = $request->cargo;  // Guardar el cargo

        // Subir foto de perfil
        if ($request->hasFile('foto_perfil')) {
            $imageName = time() . '-' . $request->file('foto_perfil')->getClientOriginalName();
            $path = public_path('empleados/img');
            $request->file('foto_perfil')->move($path, $imageName);
            $empleado->foto_perfil = 'empleados/img/' . $imageName;
        }

        // Subir contrato
        if ($request->hasFile('documento_contrato')) {
            $documentName = time() . '-' . $request->file('documento_contrato')->getClientOriginalName();
            $path = public_path('empleados/img_contratos');
            $request->file('documento_contrato')->move($path, $documentName);
            $empleado->documento_contrato = 'empleados/img_contratos/' . $documentName;
        }

        // Subir dni solo si se sube un archivo nuevo
        if ($request->hasFile('dn_file')) {
            $imageName = time() . '-' . $request->file('dn_file')->getClientOriginalName();
            $path = public_path('empleados/dni');
            $request->file('dn_file')->move($path, $imageName);
            $empleado->dn_file = 'empleados/dni/' . $imageName;
        }

        // Guardar los cambios
        $empleado->save();

        return redirect()->route('admin.empleados.index')->with('success', 'Empleado actualizado correctamente');
    }


    public function mostrarFormularioReporte()
    {
        // Obtener los campos disponibles de los empleados para mostrarlos en el formulario
        $campos = [
            'dn' => 'DNI',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'genero' => 'Genero',
            'direccion' => 'Dirección',
            'telefono' => 'Teléfono',
            'fecha_nacimiento' => 'Fecha de Nacimiento',
            'fecha_ingreso' => 'Fecha de Ingreso',
            'oficina' => 'Oficina',
            'grupo' => 'Grupo',
            'tipo_contrato' => 'Tipo de Contrato',
            'rol' => 'Rol',
            'email' => 'Email',
            'cargo' => 'Cargo',
            'vacaciones_restantes' => 'Vacaciones Restantes',
            'vacaciones_tomadas' => 'Vacaciones Tomadas',
        ];

        return view('admin.empleados.reporte_form', compact('campos'));
    }

    public function generarReporte(Request $request)
    {
        $camposSeleccionados = $request->input('campos', []);

        // Validar que al menos un campo esté seleccionado
        if (empty($camposSeleccionados)) {
            return redirect()->route('admin.empleados.reporte')->with('error', 'Debe seleccionar al menos un campo');
        }

        // Definir los campos disponibles
        $campos = [
            'dn' => 'DNI',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'genero' => 'Genero',
            'direccion' => 'Dirección',
            'telefono' => 'Teléfono',
            'fecha_nacimiento' => 'Fecha de Nacimiento',
            'fecha_ingreso' => 'Fecha de Ingreso',
            'oficina' => 'Oficina',
            'grupo' => 'Grupo',
            'tipo_contrato' => 'Tipo de Contrato',
            'rol' => 'Rol',
            'email' => 'Email',  // Agregar email
            'cargo' => 'Cargo',
            'vacaciones_restantes' => 'Vacaciones Restantes',
            'vacaciones_tomadas' => 'Vacaciones Tomadas',
        ];

        $relaciones = [];

        // Cargar relaciones adicionales según los campos seleccionados
        if (in_array('oficina', $camposSeleccionados)) {
            $relaciones[] = 'oficina';
        }
        if (in_array('grupo', $camposSeleccionados)) {
            $relaciones[] = 'grupo';
        }
        if (in_array('tipo_contrato', $camposSeleccionados)) {
            $relaciones[] = 'tipoContrato';
        }
        if (in_array('rol', $camposSeleccionados)) {
            $relaciones[] = 'user'; // Cargar la relación de User para obtener los roles
        }

        if (in_array('email', $camposSeleccionados)) {
            $relaciones[] = 'user'; // Aseguramos cargar la relación de User para obtener el email
        }

        // Cargar empleados con las relaciones necesarias
        $empleados = Empleado::with($relaciones)->get();

        // Asignar los roles y emails a los empleados
        foreach ($empleados as $empleado) {
            if (in_array('rol', $camposSeleccionados) && $empleado->user) {
                // Obtener los roles del usuario asociado al empleado
                $empleado->roles = $empleado->user->getRoleNames()->toArray(); // Obtener los roles como array
            }

            if (in_array('email', $camposSeleccionados) && $empleado->user) {
                // Obtener el email del usuario asociado al empleado
                $empleado->email = $empleado->user->email; // Agregar el email al empleado
            }
        }

        // Determinar la orientación del PDF
        $orientacion = count($camposSeleccionados) > 6 ? 'landscape' : 'portrait';

        // Generar el PDF con los datos
        $pdf = PDF::loadView('admin.empleados.reporte_pdf', compact('empleados', 'camposSeleccionados', 'campos'))
            ->setPaper('a4', $orientacion);

        // Descargar el PDF
        return $pdf->download('Reporte de empleados.pdf');
    }

    public function generarReporteExcel(Request $request)
    {
        $camposSeleccionados = $request->input('campos', []);

        if (empty($camposSeleccionados)) {
            return redirect()->route('admin.empleados.reporte')->with('error', 'Debe seleccionar al menos un campo');
        }

        // Relacionar según campos
        $relaciones = [];

        if (in_array('oficina', $camposSeleccionados)) {
            $relaciones[] = 'oficina';
        }

        if (in_array('grupo', $camposSeleccionados)) {
            $relaciones[] = 'grupo';
        }

        if (in_array('tipo_contrato', $camposSeleccionados)) {
            $relaciones[] = 'tipoContrato';
        }

        if (in_array('rol', $camposSeleccionados) || in_array('email', $camposSeleccionados)) {
            $relaciones[] = 'user';
        }

        $empleados = \App\Models\Empleado::with($relaciones)->get();

        // Agregar roles y email si se pidió
        foreach ($empleados as $empleado) {
            if (in_array('rol', $camposSeleccionados) && $empleado->user) {
                $empleado->roles = $empleado->user->getRoleNames()->toArray();
            }

            if (in_array('email', $camposSeleccionados) && $empleado->user) {
                $empleado->email = $empleado->user->email;
            }
        }

        // Generar Excel con la fecha actual
        $fechaHoy = now()->format('Y-m-d'); // Formato de fecha: Año-Mes-Día
        $nombreArchivo = 'Reporte de Empleados ' . $fechaHoy . '.xlsx';

        return Excel::download(new EmpleadosExportExcel($empleados, $camposSeleccionados), $nombreArchivo);

    }

}