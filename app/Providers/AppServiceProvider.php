<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\View;
use App\Models\Permiso;
use App\Models\Vacacion;

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
            // Obtener el conteo de las notificaciones pendientes
            $notificacionesCount = Permiso::where('estado', 'pendiente')->count() +
                Vacacion::where('estado', 'pendiente')->count();

            // Obtener las notificaciones de permisos
            $notificacionesPermisos = Permiso::where('estado', 'pendiente')
                ->with('empleado') // Asegúrate de cargar la relación de empleado
                ->get()
                ->map(function ($permiso) {
                    return [
                        'mensaje' => "{$permiso->empleado->nombre} {$permiso->empleado->apellido} solicitó un permiso",
                        'url' => url('/admin/permisos')
                    ];
                });

            // Obtener las notificaciones de vacaciones
            $notificacionesVacaciones = Vacacion::where('estado', 'pendiente')
                ->with('empleado') // Asegúrate de cargar la relación de empleado
                ->get()
                ->map(function ($vacacion) {
                    return [
                        'mensaje' => "{$vacacion->empleado->nombre} {$vacacion->empleado->apellido} solicitó vacaciones",
                        'url' => url('/admin/vacaciones')
                    ];
                });

            // Combina las dos colecciones usando concat
            $notificaciones = $notificacionesPermisos->concat($notificacionesVacaciones);

            // Si no hay notificaciones, asegurarse de que $notificaciones sea una colección vacía
            $notificaciones = $notificaciones->isEmpty() ? collect([]) : $notificaciones;

            // Pasar las variables a las vistas
            $view->with('notificacionesCount', $notificacionesCount)
                ->with('notificaciones', $notificaciones);
        });

    }
}
