<?php

namespace App\Http\Controllers;

use App\Models\Oficina;
use Illuminate\Http\Request;

class OficinaController extends Controller
{
    // Mostrar todas las oficinas
    public function index()
    {
        $oficinas = Oficina::all();  // Obtener todas las oficinas
        return view('admin.configuracion.crear_oficina.index', compact('oficinas')); // Cambia el nombre de la vista aquí
    }

    // Mostrar el formulario para crear una nueva oficina
    public function create()
    {
        return view('admin.configuracion.crear_oficina.create');  // Vista de formulario de creación de oficina
    }

    // Almacenar una nueva oficina
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',  // Validar que la dirección esté incluida
        ]);

        // Crear la oficina
        Oficina::create([
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,  // Guardar la dirección
        ]);

        // Redirigir a la lista de oficinas con un mensaje de éxito
        return redirect()->route('admin.configuracion.store_oficina.store')->with('success', 'Oficina creada con éxito');

    }

}
