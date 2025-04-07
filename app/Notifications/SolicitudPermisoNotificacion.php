<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class SolicitudPermisoNotificacion extends Notification implements ShouldQueue
{
    use Queueable;

    protected $vacacion;  // El objeto Vacacion
    protected $link;      // El link de la notificación

    public function __construct($vacacion)
    {

        $this->vacacion = $vacacion;
        $this->link = $vacacion->id; // Aseguramos que el link (ID) sea único
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nueva solicitud de vacaciones')
            ->greeting('Hola, ' . $notifiable->name)
            ->line('El empleado ' . $this->vacacion->empleado->nombre . ' ha solicitado unas vacaciones.')
            ->line('Desde: ' . \Carbon\Carbon::parse($this->vacacion->fecha_inicio)->toFormattedDateString())
            ->line('Hasta: ' . \Carbon\Carbon::parse($this->vacacion->fecha_fin)->toFormattedDateString())
            ->action('Revisar solicitud', url('/admin/notificaciones'))
            ->line('Por favor, revisa la solicitud lo antes posible.');
    }

    public function toArray($notifiable)
    {
        return [
            'mensaje' => 'El empleado ' . $this->vacacion->empleado->nombre . ' ha solicitado unas vacaciones.',
            'fecha_inicio' => \Carbon\Carbon::parse($this->vacacion->fecha_inicio)->toFormattedDateString(),
            'fecha_fin' => \Carbon\Carbon::parse($this->vacacion->fecha_fin)->toFormattedDateString(),
            'link' => url('/admin/notificaciones'),
            'estado' => $this->vacacion->estado, // Aquí agregamos el estado de la solicitud de vacaciones
            'empleado_id' => $this->vacacion->empleado->id,  // Agrega el empleado_id
        ];


    }

}
