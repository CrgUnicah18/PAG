<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\ActualizarEstadoEmpleado;
use App\Console\Commands\VacacionesActualizarEstado;
use App\Console\Commands\ActualizarVacacionesAnuales; // ← Asegúrate de tener este comando creado
use App\Jobs\AlertaVacacionesPendientes; // ← Asegúrate de tener este Job creado
use App\Mail\AlertaVacacionesPendientesMail; // ← Asegúrate de tener este Mail creado

class TaskSchedulerServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            ActualizarEstadoEmpleado::class,
            VacacionesActualizarEstado::class,
            ActualizarVacacionesAnuales::class, // ← Nuevo comando
        ]);
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
        $schedule->command(VacacionesActualizarEstado::class)->dailyAt('19:00');
        $schedule->command(ActualizarVacacionesAnuales::class)->yearlyOn(1, 1, '00:00'); // ← Nuevo comando programado
        // Registrar el Job para que se ejecute, por ejemplo, todos los días a las 6 AM.
        $schedule->job(new AlertaVacacionesPendientes)->dailyAt('08:30');
    }
}
