<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Oficina;
use App\Models\Grupo;
use App\Models\TipoContrato;
use App\Models\User; // Asegúrate de incluir el modelo User

class RegisterController extends Controller
{
    // Mostrar el formulario de registro
    public function showRegistrationForm()
    {
        // Obtener todas las oficinas
        $oficinas = Oficina::all();
        $grupos = Grupo::all();
        $tiposContrato = TipoContrato::all(); // Obtener todos los tipos de contrato
        return view('login.register', compact('oficinas', 'grupos', 'tiposContrato'));
    }

    // Procesar el formulario de registro
    public function register(Request $request)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'genero' => 'required|in:F,M',
            'estado' => 'nullable|string', // Se vuelve nullable, ya que ahora se establece por defecto en 'activo'
            'fecha_ingreso' => 'required|date|before_or_equal:' . now()->toDateString(),
            'tipo_contrato_id' => 'required|exists:tipo_contratos,id', // Validación para el tipo de contrato
            'direccion' => 'required|string',
            'telefono' => 'required|string',
            'fecha_nacimiento' => 'required|date',
            'oficina_id' => 'required|integer',
            'grupo_id' => 'required|integer',
            'foto_perfil' => 'nullable|image',
            'dn' => 'required|string|max:15|unique:empleados,dn', // Validación para el DN
            'dn_file' => 'required|mimes:pdf,jpeg,jpg,png|max:2048', // Para almacenar el archivo (fotografía o PDF)
            'cargo' => 'required|string|max:50', // Validación para el cargo
        ], [
            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'El estado debe ser uno de los siguientes: activo, inactivo.',
            'foto_perfil.image' => 'La foto de perfil debe ser una imagen válida.',
            'dn.required' => 'El DN es obligatorio.',
            'dn.unique' => 'El DN ya ha sido registrado.',
            'dn.max' => 'El DN no puede tener más de 15 caracteres.',
            'dn_file.required' => 'La fotografia o pdf del DN es obligatorio.',
            'cargo.required' => 'El cargo es obligatorio.',
            'cargo.max' => 'El cargo no puede tener más de 50 caracteres.',
        ]);

        // Si la validación falla, redirigir de vuelta con errores
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Obtener el nombre del tipo de contrato
        $tipoContrato = TipoContrato::find($request->tipo_contrato_id);

        // Verificar si el empleado ya tiene un usuario asociado
        $empleadoExistente = Empleado::find($request->empleado_id);
        if ($empleadoExistente && $empleadoExistente->user) {
            // Si ya tiene un usuario asociado, redirigir
            return redirect()->route('empleado.inicio.home')->with('error', 'Este empleado ya tiene un usuario creado.');
        }

        // Crear el empleado
        $empleado = Empleado::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'genero' => $request->genero,
            'estado' => 'activo', // Estado por defecto
            'fecha_ingreso' => $request->fecha_ingreso,
            'tipo_contrato_id' => $request->tipo_contrato_id,
            'tipo_contrato' => $tipoContrato->nombre, // Guardar el nombre del tipo de contrato
            'vacaciones_tomadas' => 0, // Inicializamos las vacaciones tomadas
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'oficina_id' => $request->oficina_id,
            'grupo_id' => $request->grupo_id,
            'foto_perfil' => $request->foto_perfil,
            'documento_contrato' => null, // Se mantiene oculto
            'vacaciones_restantes' => 0, // Inicializamos las vacaciones restantes
            'dn' => $request->dn, // Agregar el DN
            'dn_file' => $request->dn_file, // Agregar el archivo del DN
            'cargo' => $request->cargo, // Agregar el cargo
        ]);

        // Redirigir a la vista para la creación del usuario
        return redirect()->route('usuario.create', ['empleado_id' => $empleado->id]);
    }
}
