<?php

namespace App\Http\Controllers;


class NotificacionController extends Controller
{
    // Método que muestra las notificaciones
    public function index()
    {
        $usuario = auth()->user(); // Obtener el usuario autenticado

        // Obtener todas las notificaciones del usuario pero filtrando solo las de estado 'pendiente' o 'pendientes_aprobacion' y no leídas
        $notificaciones = $usuario->notifications->filter(function ($notificacion) {
            // Asegúrate de que la notificación tiene un 'estado', que esté en los estados deseados y que no esté leída
            return isset($notificacion->data['estado']) && in_array($notificacion->data['estado'], ['pendiente', 'pendientes_aprobacion']) && !$notificacion->read_at;
        });

        // Redirigir a la vista correspondiente según el rol
        if ($usuario->hasRole('admin')) {
            return view('admin.notificaciones.index', compact('notificaciones'));
        }

        if ($usuario->hasRole('supervisor')) {
            return view('supervisor.notificaciones.index', compact('notificaciones'));
        }

        if ($usuario->hasRole('empleado')) {
            return view('empleado.notificaciones.index', compact('notificaciones'));
        }

        // Si no tiene un rol asignado, redirigir o mostrar un error
        return redirect()->route('home');
    }


    // Método que marca la notificación como leída y redirige
    public function leerNotificacion($id)
    {
        $usuario = auth()->user();
        $notificacion = $usuario->notifications()->find($id);

        if ($notificacion) {
            // Solo marcar como leída esta notificación
            $notificacion->markAsRead();
        }

        // Redirigir a la vista admin.vacaciones.index después de marcarla como leída
        return redirect()->route('admin.vacaciones.index');
    }

}
