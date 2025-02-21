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
        Permiso::create($request->all());
        return redirect()->route('admin.permisos.index');
    }

    public function edit(Permiso $permiso)
    {
        return view('admin.permisos.edit', compact('permiso'));
    }

    public function update(Request $request, Permiso $permiso)
    {
        $permiso->update([
            'estado' => $request->estado,
            'comentario' => $request->comentario,
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
        $permiso->update(['estado' => 'aprobado']);

        return redirect()->route('admin.permisos.index')->with('success', 'Solicitud aprobada');
    }

    // Método para declinar la solicitud
    public function declinar($id)
    {
        $permiso = Permiso::findOrFail($id);
        $permiso->update(['estado' => 'rechazado']);

        return redirect()->route('admin.permisos.index')->with('success', 'Solicitud rechazada');
    }
}
