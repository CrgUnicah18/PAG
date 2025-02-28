<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Spatie\Permission\Traits\HasRoles;


class LoginController extends Controller
{
    // Mostrar el formulario de login
    public function showLoginForm()
    {
        return view('login.login');  // Vista en la carpeta 'login'
    }

    // Manejar el envío del formulario de login
    public function login(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Intentar hacer login con las credenciales proporcionadas
        if (Auth::attempt($request->only('email', 'password'))) {
            // Obtener usuario autenticado
            $user = Auth::user();

            // Redirigir al usuario según su rol después de autenticarse
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.inicio.home');
            } elseif ($user->hasRole('supervisor')) {
                return redirect()->route('supervisor.inicio.home');
            } elseif ($user->hasRole('empleado')) {
                return redirect()->route('empleado.inicio.home');
            }
            // Si el usuario no tiene un rol específico, redirigir a una ruta por defecto
            return redirect()->intended('/');
        }

        // Si la autenticación falla
        return back()->withErrors(['email' => 'Las credenciales no coinciden.']);
    }

}
