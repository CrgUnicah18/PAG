<?php

namespace App\Jobs;

use App\Models\Empleado;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\AlertaVacacionesPendientesMail;

class AlertaVacacionesPendientes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle()
    {
        $empleados = Empleado::all();  // Obtenemos todos los empleados
        $currentYear = date('Y');      // Obtenemos el año actual

        foreach ($empleados as $empleado) {
            // Usamos el campo vacaciones_restantes que está en la tabla empleados
            if ($empleado->vacaciones_restantes > 0) {
                $fechaLimite = strtotime("31-12-$currentYear");  // Fecha límite de fin de año
                $fechaActual = time();  // Fecha actual

                \Log::info("Revisando vacaciones para {$empleado->nombre} - Fecha actual: {$fechaActual}, Fecha límite: {$fechaLimite}");

                // Verificamos si las vacaciones están a punto de vencer
                if ($fechaActual >= $fechaLimite - 90 * 24 * 60 * 60) {
                    // Obtener el correo electrónico del usuario relacionado con el empleado
                    $user = $empleado->user;  // Relación con el modelo User
                    if ($user) {
                        try {
                            // Enviar correo de alerta
                            Mail::to($user->email)->send(new AlertaVacacionesPendientesMail($empleado));
                            \Log::info('Correo enviado a: ' . $user->email);
                        } catch (\Exception $e) {
                            \Log::error('Error al enviar correo a: ' . $user->email . '. Error: ' . $e->getMessage());
                        }
                    } else {
                        \Log::info("No se encontró usuario para el empleado {$empleado->nombre}");
                    }
                } else {
                    \Log::info("Aún no es tiempo de enviar correo a {$empleado->nombre}");
                }
            } else {
                \Log::info("No tiene vacaciones pendientes el empleado {$empleado->nombre}");
            }
        }
    }

}
