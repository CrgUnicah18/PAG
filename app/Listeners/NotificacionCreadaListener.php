<?php

namespace App\Listeners;

use App\Events\NotificacionCreada;
use Illuminate\Support\Facades\Session;

class NotificacionCreadaListener
{
    public function handle(NotificacionCreada $event)
    {
        // Aquí puedes enviar la notificación a los administradores
        // Por ejemplo, agregar un mensaje flash para mostrarlo en la interfaz
        session()->flash('notify', [
            ['type' => 'success', 'message' => $event->mensaje],
        ]);
    }
}
