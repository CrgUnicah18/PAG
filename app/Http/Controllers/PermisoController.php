<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    public function index(Request $request)
    {
        $permisos = Permiso::when($request->estado, function ($query, $estado) {
            return $query->where('estado', $estado);
        })
            ->with(['empleado', 'tipoPermiso'])
            ->get();

        return view('admin.permisos.index', compact('permisos'));
    }

    public function create()
    {
        return view('admin.permisos.create');
    }

    public function store(Request $request)
    {
        // Aquí no es necesario cambiar nada en esta función, ya que estás creando el permiso desde cero
        Permiso::create($request->all());
        return redirect()->route('admin.permisos.index');
    }

    public function edit(Permiso $permiso)
    {
        return view('admin.permisos.edit', compact('permiso'));
    }

    public function update(Request $request, Permiso $permiso)
    {
        // Solo actualizamos el comentario, sin cambiar el estado a menos que se indique
        $permiso->update([
            'comentario' => $request->comentario, // Solo actualizamos el comentario
            // 'estado' => $request->estado, // Si no deseas cambiar el estado aquí, lo puedes dejar comentado o eliminarlo
        ]);

        return redirect()->route('admin.permisos.index');
    }

    public function destroy(Permiso $permiso)
    {
        $permiso->delete();
        return redirect()->route('admin.permisos.index');
    }

    // Método para aprobar la solicitud
    public function aprobar($id)
    {
        $permiso = Permiso::findOrFail($id);
        // Solo actualizamos el estado a 'aprobado'
        $permiso->update(['estado' => 'aprobado']);

        return redirect()->route('admin.permisos.index')->with('success', 'Solicitud aprobada');
    }

    // Método para declinar la solicitud
    public function declinar($id)
    {
        $permiso = Permiso::findOrFail($id);
        // Solo actualizamos el estado a 'rechazado'
        $permiso->update(['estado' => 'rechazado']);

        return redirect()->route('admin.permisos.index')->with('success', 'Solicitud rechazada');
    }
    // Método para agregar comentario a un permiso
    public function addComentario(Request $request, Permiso $permiso)
    {
        // Validar el comentario
        $validated = $request->validate([
            'comentario' => 'required|string|max:1000',
        ]);

        // Guardar el comentario en el permiso
        $permiso->comentario = $validated['comentario'];
        $permiso->save();

        // Redirigir al listado de permisos con un mensaje de éxito
        return redirect()->route('admin.permisos.index')->with('success', 'Comentario agregado correctamente.');
    }
}
