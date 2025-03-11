<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\ActualizarEstadoEmpleado;
use App\Console\Commands\VacacionesActualizarEstado; // ← Asegúrate de tener este comando creado

class TaskSchedulerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Schedule $schedule)
    {
        // Registrar los comandos programados
        $schedule->command(ActualizarEstadoEmpleado::class)->dailyAt('19:00');
        $schedule->command(VacacionesActualizarEstado::class)->dailyAt('19:00'); // ← Nuevo comando
    }
}
