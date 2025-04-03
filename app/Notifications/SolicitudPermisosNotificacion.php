<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Permiso;
use App\Models\Empleado;

class SolicitudPermisosNotificacion extends Notification implements ShouldQueue
{
    use Queueable;

    protected $permiso;
    protected $empleado;

    /**
     * Create a new notification instance.
     */
    public function __construct(Permiso $permiso)
    {
        $this->permiso = $permiso;
        $this->empleado = Empleado::find($permiso->empleado_id);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('Nueva Solicitud de Permiso')
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Se ha registrado una nueva solicitud de permiso por parte de ' . $this->empleado->nombre . '.')
            ->line('**Tipo de Permiso:** ' . $this->permiso->tipoPermiso->nombre)
            ->line('**Fecha Inicio:** ' . $this->permiso->fecha_inicio)
            ->line('**Fecha Fin:** ' . $this->permiso->fecha_fin)
            ->line('**Comentario:** ' . ($this->permiso->comentario ?? 'Ninguno'))
            ->action('Revisar Solicitud', url('/admin/permisos/' . $this->permiso->id))
            ->line('Por favor, revisa la solicitud y toma la acción correspondiente.');

        // Si hay archivo adjunto, agregar un enlace
        if ($this->permiso->subsidio_archivo) {
            $mail->line('Este permiso incluye un documento de subsidio.')
                ->action('Ver Archivo', url('/storage/' . $this->permiso->subsidio_archivo));
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        // Definir la URL de revisión según el rol del usuario
        $url = ($notifiable->hasRole('supervisor'))
            ? url('/supervisor/permisos/' . $this->permiso->id)
            : url('/admin/permisos/' . $this->permiso->id);

        return [
            'permiso_id' => $this->permiso->id,
            'empleado' => $this->empleado->nombre,
            'tipo_permiso' => $this->permiso->tipoPermiso->nombre,
            'fecha_inicio' => $this->permiso->fecha_inicio,
            'fecha_fin' => $this->permiso->fecha_fin,
            'comentario' => $this->permiso->comentario,
            'url' => $url, // Agregamos la URL para que el botón funcione correctamente
        ];
    }

}
