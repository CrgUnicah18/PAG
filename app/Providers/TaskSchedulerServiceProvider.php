<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\ActualizarEstadoEmpleado;

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
        // Registrar el comando programado
        $schedule->command(ActualizarEstadoEmpleado::class)->dailyAt('19:00'); // Ajusta la hora según lo necesites
    }
}
