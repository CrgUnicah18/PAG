<?php

namespace App\Http\Controllers;

use App\Models\TipoPermiso;
use Illuminate\Http\Request;

class TipoPermisoController extends Controller
{
    public function index()
    {
        $tiposPermiso = TipoPermiso::all();
        return view('configuracion.tipos-permisos.index', compact('tiposPermiso'));
    }

    public function show($id)
    {
        // Buscar el tipo de permiso por id
        $tipoPermiso = TipoPermiso::findOrFail($id);

        // Retornar la vista con el tipo de permiso encontrado
        return view('configuracion.tipos-permisos.show', compact('tipoPermiso'));
    }


    public function create()
    {
        return view('configuracion.tipos-permisos.create');
    }

    public function store(Request $request)
    {
        // Aquí va la validación
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'dias' => 'required|integer|min:1', // Asegúrate de que 'dias' sea un número válido
        ]);

        TipoPermiso::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'dias' => $request->dias, // Asegúrate de guardar el valor de 'dias'
        ]);

        return redirect()->route('configuracion.tipos-permisos.index')->with('success', 'Tipo de permiso creado correctamente.');
    }
    public function edit($id)
    {
        $tipoPermiso = TipoPermiso::findOrFail($id);  // Encontrar el tipo de permiso
        return view('configuracion.tipos-permisos.edit', compact('tipoPermiso')); // Pasar la variable a la vista
    }

    public function update(Request $request, $id)
    {
        $tipoPermiso = TipoPermiso::findOrFail($id);  // Encontrar el tipo de permiso
        $tipoPermiso->update($request->all());  // Actualizar los datos
        return redirect()->route('configuracion.tipos-permisos.index')->with('success', 'Tipo de permiso actualizado con éxito.');
    }




    //public function edit(TipoPermiso $tipoPermiso)
    //{
    //    return view('configuracion.tipos-permisos.edit', compact('tipoPermiso'));
    //}
/*
    public function update(Request $request, TipoPermiso $tipoPermiso)
    {
        $request->validate([
            'nombre' => 'required',
            'descripcion' => 'required',
            'dias' => 'required|integer',
        ]);

        $tipoPermiso->update($request->all());

        // Actualizar solo los campos que nos interesan
        $tipoPermiso->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'dias' => $request->dias, // Asegúrate de que 'dias' está siendo actualizado también
        ]);

        return redirect()->route('configuracion.tipos-permisos.index')->with('success', 'Tipo de permiso actualizado correctamente.');
    }

*/
    public function destroy(TipoPermiso $tipoPermiso)
    {
        $tipoPermiso->delete();

        return redirect()->route('configuracion.tipos-permisos.index')->with('success', 'Tipo de permiso eliminado correctamente');
    }
}