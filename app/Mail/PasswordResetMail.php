<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class PasswordResetMail extends Mailable
{
    public $pin; // Hacer pública la propiedad $pin
    public $url; // Hacer pública la propiedad $url

    public function __construct($pin, $url)
    {
        $this->pin = $pin; // Asignar el valor del PIN al atributo $pin
        $this->url = $url; // Asignar el valor de la URL al atributo $url
    }

    public function build()
    {
        return $this->view('emails.password_reset')
            ->with(['pin' => $this->pin, 'url' => $this->url]); // Pasar el PIN y la URL a la vista
    }
}

