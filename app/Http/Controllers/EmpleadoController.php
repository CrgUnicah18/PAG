<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Oficina;
use App\Models\Grupo;
use App\Models\TipoContrato;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $query = Empleado::query();

        if ($request->has('nombre') && !empty($request->nombre)) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->has('grupo_id') && !empty($request->grupo_id)) {
            $query->where('grupo_id', $request->grupo_id);
        }

        if ($request->has('oficina_id') && !empty($request->oficina_id)) {
            $query->where('oficina_id', $request->oficina_id);
        }
        // Filtro por estado
        if ($request->has('estado') && !empty($request->estado)) {
            $query->where('estado', $request->estado);
        }

        $empleados = $query->with('supervisor')->get();
        $grupos = Grupo::all();
        $oficinas = Oficina::all();
        $tiposContratos = TipoContrato::all(); // Obtener todos los tipos de contrato

        return view('admin.empleados.index', [
            'empleados' => $empleados,
            'grupos' => $grupos,
            'oficinas' => $oficinas,
            'tiposContratos' => $tiposContratos, // Pasar los tipos de contrato a la vista
        ]);
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
        $empleado = Empleado::findOrFail($id);
        $oficinas = Oficina::all();
        $grupos = Grupo::all();
        $tiposContratos = TipoContrato::all();

        return view('admin.empleados.show', [
            'empleado' => $empleado,
            'oficinas' => $oficinas,
            'grupos' => $grupos,
            'tiposContratos' => $tiposContratos,
        ]);
    }

    public function storeEmpleado(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'oficina_id' => 'required|exists:oficinas,id',
            'grupo_id' => 'required|exists:grupos,id',
            'supervisor_id' => 'nullable|exists:empleados,id',
            'tipo_contrato_id' => 'required|exists:tipo_contratos,id',
            'fecha_ingreso' => 'required|date',
            'estado' => 'required|in:activo,inactivo',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'documento_contrato' => 'nullable|mimes:pdf,doc,docx',
        ]);

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
        $empleado->fecha_ingreso = $request->fecha_ingreso;
        $empleado->estado = $request->estado;

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

        $empleado->save();

        return redirect()->route('admin.createUsuario', ['empleado_id' => $empleado->id]);
    }


    // Función para mostrar el formulario de creación de usuario para el empleado específico
    public function createUsuario($empleado_id)
    {
        // Buscar al empleado con el ID pasado
        $empleado = Empleado::findOrFail($empleado_id);

        // Pasar el empleado a la vista para crear el usuario
        return view('admin.createUsuario', compact('empleado_id'));
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

        // Redirigir a la vista de empleados con un mensaje de éxito
        return redirect()->route('admin.empleados.index')->with('success', 'Usuario creado exitosamente.');
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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'grupo_id' => 'required|exists:grupos,id',
            'oficina_id' => 'required|exists:oficinas,id',
            'supervisor_id' => 'nullable|exists:empleados,id',
            'estado' => 'required|in:activo,inactivo',
            'tipo_contrato_id' => 'required|exists:tipo_contratos,id',
            'fecha_ingreso' => 'required|date',
            'foto_peril' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'documento_contrato' => 'nullable|mimes:pdf,doc,docx,zip|max:2048', // Solo PDF, DOC, DOCX, ZIP
        ]);

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

        // Subir foto de perfil
        if ($request->hasFile('foto_perfil')) {
            $imageName = time() . '-' . $request->file('foto_perfil')->getClientOriginalName();
            $path = public_path('empleados/img');  // Ruta donde se guardará el archivo
            $request->file('foto_perfil')->move($path, $imageName);  // Mover el archivo a la carpeta deseada
            $empleado->foto_perfil = 'empleados/img/' . $imageName;  // Guardar la ruta en la base de datos sin 'public/'
        }

        // Subir contrato
        if ($request->hasFile('documento_contrato')) {
            $documentName = time() . '-' . $request->file('documento_contrato')->getClientOriginalName();
            $path = public_path('empleados/img_contratos');  // Ruta donde se guardará el archivo
            $request->file('documento_contrato')->move($path, $documentName);  // Mover el archivo a la carpeta deseada
            $empleado->documento_contrato = 'empleados/img_contratos/' . $documentName;  // Guardar la ruta en la base de datos sin 'public/'
        }



        // Guardar los cambios
        $empleado->save();


        return redirect()->route('admin.empleados.index')->with('success', 'Empleado actualizado correctamente');
    }

    public function mostrarFormularioReporte()
    {
        // Obtener los campos disponibles de los empleados para mostrarlos en el formulario
        $campos = [
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'direccion' => 'Dirección',
            'telefono' => 'Teléfono',
            'fecha_nacimiento' => 'Fecha de Nacimiento',
            'fecha_ingreso' => 'Fecha de Ingreso',
            'oficina' => 'Oficina',
            'grupo' => 'Grupo',
            'tipo_contrato' => 'Tipo de Contrato',
        ];

        return view('admin.empleados.reporte_form', compact('campos'));
    }

    public function generarReporte(Request $request)
    {
        $camposSeleccionados = $request->input('campos', []);

        if (empty($camposSeleccionados)) {
            return redirect()->route('admin.empleados.reporte')->with('error', 'Debe seleccionar al menos un campo');
        }

        // Definir los campos disponibles y sus relaciones
        $campos = [
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'direccion' => 'Dirección',
            'telefono' => 'Teléfono',
            'fecha_nacimiento' => 'Fecha de Nacimiento',
            'fecha_ingreso' => 'Fecha de Ingreso',
            'oficina' => 'Oficina',
            'grupo' => 'Grupo',
            'tipo_contrato' => 'Tipo de Contrato',
        ];

        // Definir qué relaciones cargar si se seleccionan
        $relaciones = [];

        if (in_array('oficina', $camposSeleccionados)) {
            $relaciones[] = 'oficina';
        }
        if (in_array('grupo', $camposSeleccionados)) {
            $relaciones[] = 'grupo';
        }
        if (in_array('tipo_contrato', $camposSeleccionados)) {
            $relaciones[] = 'tipoContrato'; // Asegúrate de que este sea el nombre correcto de la relación en el modelo
        }

        // Cargar empleados con relaciones necesarias
        $empleados = Empleado::with($relaciones)->get();

        // Determinar la orientación del PDF (vertical si ≤ 6 columnas, horizontal si > 6)
        $orientacion = count($camposSeleccionados) > 6 ? 'landscape' : 'portrait';

        // Generar el PDF con la orientación dinámica
        $pdf = PDF::loadView('admin.empleados.reporte_pdf', compact('empleados', 'camposSeleccionados', 'campos'))
            ->setPaper('a4', $orientacion);

        return $pdf->download('reporte_empleados.pdf');
    }

}
