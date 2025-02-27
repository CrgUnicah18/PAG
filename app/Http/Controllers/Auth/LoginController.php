<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return redirect()->intended('/admin/inicio');  // Redirigir a la página principal
        }

        // Si la autenticación falla
        return back()->withErrors(['email' => 'Las credenciales no coinciden.']);
    }
}
