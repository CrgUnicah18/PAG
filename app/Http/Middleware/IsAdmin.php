<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
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
                // Ahora puedes usar el método hasRole sin problemas
                if ($user->hasRole('admin')) {
                    return $next($request);
                }
            }
        }

        return redirect()->route('login');


    }
}
