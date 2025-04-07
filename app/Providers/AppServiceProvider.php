<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Models\Permiso;
use App\Models\Vacacion;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\IsAdmin;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar el middleware 'admin' directamente desde el AppServiceProvider
        Route::aliasMiddleware('admin', \App\Http\Middleware\IsAdmin::class);
        // Registrar el middleware 'admin' directamente desde el AppServiceProvider
        Route::aliasMiddleware('supervisor', \App\Http\Middleware\IsAdmin::class);


        View::composer('*', function ($view) {
            $user = Auth::user();

            if (!$user || !$user->empleado) {
                $view->with('notificacionesCount', 0)->with('notificaciones', collect([]));
                return;
            }

            $empleadoActual = $user->empleado;

            // Inicializamos variables
            $permisos = collect();
            $vacaciones = collect();

            if ($user->hasRole('admin')) {
                // Admin ve todo
                $permisos = Permiso::where('estado', 'pendiente')->with('empleado')->get();
                $vacaciones = Vacacion::where('estado', 'pendiente')->with('empleado')->get();

            } elseif ($user->hasRole('supervisor')) {
                // Supervisor solo ve solicitudes de sus empleados
                $permisos = Permiso::where('estado', 'pendiente')
                    ->whereHas('empleado', function ($query) use ($empleadoActual) {
                        $query->where('supervisor_id', $empleadoActual->id);
                    })
                    ->with('empleado')
                    ->get();

                $vacaciones = Vacacion::where('estado', 'pendiente')
                    ->whereHas('empleado', function ($query) use ($empleadoActual) {
                        $query->where('supervisor_id', $empleadoActual->id);
                    })
                    ->with('empleado')
                    ->get();

            } elseif ($user->hasRole('empleado')) {
                // Empleado solo ve sus propias solicitudes
                $permisos = Permiso::where('estado', 'pendiente')
                    ->where('empleado_id', $empleadoActual->id)
                    ->with('empleado')
                    ->get();

                $vacaciones = Vacacion::where('estado', 'pendiente')
                    ->where('empleado_id', $empleadoActual->id)
                    ->with('empleado')
                    ->get();
            }

            // Formatear notificaciones
            $notificacionesPermisos = $permisos->map(function ($permiso) {
                return [
                    'mensaje' => "{$permiso->empleado->nombre} {$permiso->empleado->apellido} solicitó un permiso",
                    'url' => url('/admin/permisos')
                ];
            });

            $notificacionesVacaciones = $vacaciones->map(function ($vacacion) {
                return [
                    'mensaje' => "{$vacacion->empleado->nombre} {$vacacion->empleado->apellido} solicitó vacaciones",
                    'url' => url('/admin/vacaciones')
                ];
            });

            // Combinar y contar
            $notificaciones = $notificacionesPermisos->concat($notificacionesVacaciones);
            $notificacionesCount = $notificaciones->count();

            $view->with('notificacionesCount', $notificacionesCount)
                ->with('notificaciones', $notificaciones);
        });

    }
}
