<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Vacacion;
use App\Models\Permiso;

class NotificacionController extends Controller
{
    public function indexPermisos()
    {
        $usuario = auth()->user();

        if ($usuario->hasRole('admin')) {
            $permisos = $this->getPermisosAdmin();
            return view('admin.notificaciones.indexpermisos', compact('permisos'));
        }

        if ($usuario->hasRole('supervisor')) {
            $permisos = $this->getPermisosSupervisor($usuario);
            return view('supervisor.notificaciones.indexpermisos', compact('permisos'));
        }
    }

    public function indexVacaciones()
    {
        $usuario = auth()->user();

        if ($usuario->hasRole('admin')) {
            $vacaciones = $this->getVacacionesAdmin();
            return view('admin.notificaciones.indexvacaciones', compact('vacaciones'));
        }

        if ($usuario->hasRole('supervisor')) {
            $vacaciones = $this->getVacacionesSupervisor($usuario);
            return view('supervisor.notificaciones.indexvacaciones', compact('vacaciones'));
        }
    }

    private function getPermisosAdmin()
    {
        return Permiso::whereIn('estado', ['pendiente', 'pendientes_aprobacion'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);
    }

    private function getVacacionesAdmin()
    {
        return Vacacion::whereIn('estado', ['pendiente', 'pendientes_aprobacion'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);
    }

    private function getPermisosSupervisor($usuario)
    {
        $empleados = Empleado::where('supervisor_id', $usuario->id)->pluck('id');
        return Permiso::whereIn('empleado_id', $empleados)
            ->whereIn('estado', ['pendiente', 'pendientes_aprobacion'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function getVacacionesSupervisor($usuario)
    {
        $empleados = Empleado::where('supervisor_id', $usuario->id)->pluck('id');
        return Vacacion::whereIn('empleado_id', $empleados)
            ->whereIn('estado', ['pendiente', 'pendientes_aprobacion'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function leerNotificacion($id)
    {
        $usuario = auth()->user();
        $notificacion = $usuario->notifications()->find($id);

        if ($notificacion) {
            $notificacion->markAsRead();

            // Verifica ambos nombres de campos o estandariza a 'url'
            if (isset($notificacion->data['url'])) {
                return redirect($notificacion->data['url'])->with('success', 'Notificación marcada como leída.');
            } elseif (isset($notificacion->data['link'])) {
                return redirect($notificacion->data['link'])->with('success', 'Notificación marcada como leída.');
            }
        } else {
            return redirect()->back()->with('error', 'No se encontró la notificación.');
        }

        return $this->redirectByRole($usuario);
    }

    private function redirectByRole($usuario)
    {
        if ($usuario->hasRole('admin')) {
            return redirect()->route('admin.vacaciones.index')->with('success', 'Notificación marcada como leída.');
        }
        if ($usuario->hasRole('supervisor')) {
            return redirect()->route('supervisor.vacaciones.index')->with('success', 'Notificación marcada como leída.');
        }
        if ($usuario->hasRole('empleado')) {
            return redirect()->route('empleado.notificaciones.index')->with('success', 'Notificación marcada como leída.');
        }

        return redirect()->route('home')->with('info', 'Notificación marcada como leída.');
    }
}
