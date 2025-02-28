<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->guard('web')->check()) {
            /** @var \App\Models\User|null $user */
            $user = auth()->user();

            // Asegúrate de que el usuario es una instancia de la clase User
            if ($user instanceof User) {
                // Verificar el rol del usuario y redirigir según corresponda
                if ($user->hasRole('admin')) {
                    // Accede a la sección de administración
                    return $next($request);
                } elseif ($user->hasRole('supervisor')) {
                    // Accede a la sección de supervisor
                    return $next($request);
                } elseif ($user->hasRole('empleado')) {
                    // Accede a la sección de empleado
                    return $next($request);
                }
            }
        }

        // Si no tiene uno de esos roles, redirigir al login
        return redirect()->route('login');
    }
}



/* Asegúrate de que el usuario es una instancia de la clase User
if ($user instanceof User) {
    // Verificar si el usuario es admin o supervisor
    if ($user->hasRole('admin') || $user->hasRole('supervisor')) {
        return $next($request);
    }
}
    */