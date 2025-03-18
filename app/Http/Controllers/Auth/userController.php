<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // Mostrar el formulario para crear el usuario
    public function create($empleado_id)
    {
        // Obtener el empleado por ID
        $empleado = Empleado::findOrFail($empleado_id);

        return view('login.crear_usuario', compact('empleado'));


    }

    // Procesar la creación del usuario
    public function store(Request $request, $empleado_id)
    {
        // Validar los datos del formulario
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $request->name, // Usamos el nombre completo, ya que el empleado ya tiene su nombre
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'empleado_id' => $empleado_id, // Vinculamos al empleado creado
        ]);

        // Asignar el rol de 'empleado'
        $role = Role::where('name', 'empleado')->first();
        if ($role) {
            $user->assignRole($role);
        }

        // Redirigir a la página principal de empleados
        return redirect()->route('empleado.inicio.home');
    }
}