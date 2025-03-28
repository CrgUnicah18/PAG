<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class EstadoSolicitudNotificacion extends Notification
{
    use Queueable;

    protected $vacacion;
    protected $estado;

    public function __construct($vacacion, $estado)
    {
        $this->vacacion = $vacacion;
        $this->estado = $estado; // Puede ser 'aprobadas', 'rechazadas', 'pendiente', etc.
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $fechaInicio = \Carbon\Carbon::parse($this->vacacion->fecha_inicio)->toFormattedDateString();
        $fechaFin = \Carbon\Carbon::parse($this->vacacion->fecha_fin)->toFormattedDateString();

        if ($this->estado == 'pendientes_aprobacion') {
            // Personaliza la notificación si el estado es "pre aprobadas por tu supervisor"
            return (new MailMessage)
                ->subject('Estado de tu solicitud de vacaciones')
                ->greeting('Hola, ' . $notifiable->name)
                ->line("Tu solicitud de vacaciones para el período del $fechaInicio al $fechaFin está pre aprobadas por tu supervisor.")
                ->action('Ver detalles', url('/empleado/notificaciones'))
                ->line('Tu supervisor está esperando la aprobación final.');
        } else {
            return (new MailMessage)
                ->subject('Estado de tu solicitud de vacaciones')
                ->greeting('Hola, ' . $notifiable->name)
                ->line('Tu solicitud de vacaciones para el período del ' . \Carbon\Carbon::parse($this->vacacion->fecha_inicio)->toFormattedDateString() . ' al ' . \Carbon\Carbon::parse($this->vacacion->fecha_fin)->toFormattedDateString())
                ->line('Han sido ' . $this->estado . '.')
                ->action('Ver detalles', url('/empleado/notificaciones'))
                ->line('Si tienes alguna pregunta, por favor contáctanos.');
        }

    }

    public function toArray($notifiable)
    {
        return [
            'mensaje' => 'Tu solicitud de vacaciones para el período del ' . \Carbon\Carbon::parse($this->vacacion->fecha_inicio)->toFormattedDateString() . ' al ' . \Carbon\Carbon::parse($this->vacacion->fecha_fin)->toFormattedDateString() . ' ha sido ' . $this->estado . '.',
            'estado' => $this->estado,
            'link' => url('/empleado/notificaciones')
        ];
    }
}
