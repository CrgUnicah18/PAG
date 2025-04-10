<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    protected $levels = [
        // Puedes personalizar los niveles de log
    ];

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Si la sesión expiró (TokenMismatch)
        if ($exception instanceof TokenMismatchException) {
            return redirect()->route('login')->with('message', 'Tu sesión ha expirado. Por favor, inicia sesión de nuevo.');
        }

        return parent::render($request, $exception);
    }
}
