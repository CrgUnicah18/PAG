<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificacionCreada implements ShouldBroadcast
{
    public $mensaje;
    public $url;

    public function __construct($mensaje, $url)
    {
        $this->mensaje = $mensaje;
        $this->url = $url;
    }

    public function broadcastOn()
    {
        return ['notificaciones'];
    }
}

