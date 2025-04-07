<?php

// app/View/Components/NotifyMessages.php

namespace App\View\Components;

use Illuminate\View\Component;

class NotifyMessages extends Component
{
    public $messages;

    public function __construct()
    {
        // Obtener los mensajes de la sesión
        $this->messages = session('notify', []);
    }

    public function render()
    {
        return view('components.notify-messages');
    }
}
