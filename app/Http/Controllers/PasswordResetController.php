<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail; // Asegúrate de importar la clase de Mail
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class PasswordResetController extends Controller
{

    // Mostrar el formulario para ingresar el correo
    public function showForm()
    {
        return view('emails.forgot-password');
    }
    // Enviar el PIN al correo del usuario
    // PasswordResetController.php
    public function sendResetPin(Request $request)
    {
        // Validar el correo
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Generar un PIN aleatorio
        $pin = rand(100000, 999999);

        // Guardar el PIN temporalmente en la base de datos
        DB::table('users')
            ->where('email', $request->email)
            ->update([
                'reset_pin' => $pin,
                'pin_expiration' => now()->addMinutes(30) // Expira en 30 minutos
            ]);

        // Generar la URL de restablecimiento
        $url = route('password.reset.form', ['token' => $pin]); // Aquí pasas el PIN como token

        // Enviar el correo con el PIN y la URL
        Mail::to($request->email)->send(new PasswordResetMail($pin, $url));

        // Redirigir al usuario con un mensaje de éxito
        return redirect()->route('login')->with('status', 'Te hemos enviado un correo con el enlace para restablecer tu contraseña. Sigue las instrucciones allí.');
    }


    // En PasswordResetController.php

    public function showResetForm($token)
    {
        // Verificar si el token está expirado o ya ha sido usado
        $user = DB::table('users')->where('reset_pin', $token)->first();

        if (!$user || $user->pin_expiration <= now()) {
            // Si el token ya fue usado o expiró, redirigir al login o a una página de error
            return redirect()->route('login')->with('error', 'Este enlace de restablecimiento ya ha expirado o es inválido.');
        }

        // El token se pasa a la vista para permitir que se valide cuando el usuario lo envíe
        return view('emails.reset', ['token' => $token]);
    }



    public function reset(Request $request)
    {
        // Validar el PIN y la nueva contraseña
        $request->validate([
            'pin' => 'required|digits:6', // Validar que el PIN sea de 6 dígitos
            'new_password' => 'required|min:8|confirmed', // Asegurarse de que la contraseña tenga al menos 8 caracteres
        ]);

        // Verificar si el PIN es válido y no ha expirado
        $user = DB::table('users')->where('reset_pin', $request->pin)
            ->where('pin_expiration', '>', now()) // Asegurarse de que el PIN no esté expirado
            ->first();

        if (!$user) {
            return redirect()->back()->withErrors(['pin' => 'PIN inválido o expirado']);
        }

        // Si el PIN es válido, actualizar la contraseña
        DB::table('users')
            ->where('email', $user->email)
            ->update([
                'password' => Hash::make($request->new_password),
                'reset_pin' => null, // Limpiar el PIN después de usarlo
                'pin_expiration' => now(), // Establecer la expiración del PIN para marcarlo como usado
            ]);

        // Redirigir al usuario a la página de login con un mensaje de éxito
        return redirect()->route('login')->with('status', 'Contraseña restablecida con éxito');
    }




}

