<?php

namespace App\Http\Controllers;

use App\Models\TipoContrato;
use Illuminate\Http\Request;

class TiposContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposContratos = TipoContrato::all(); // Obtener todos los tipos de contrato
        return view('admin.configuracion.tipos_contratos.index', compact('tiposContratos')); // Retornar vista
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.configuracion.tipos_contratos.create'); // Formulario para crear un tipo de contrato
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255', // Validación del nombre
        ]);

        TipoContrato::create($validated); // Crear nuevo tipo de contrato

        return redirect()->route('admin.configuracion.tipos-contratos.index')->with('success', 'Tipo de contrato creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoContrato $tipoContrato)
    {
        return view('admin.configuracion.tipos_contratos.show', compact('tipoContrato')); // Mostrar tipo de contrato específico
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TipoContrato $tipoContrato)
    {
        // Obtener todos los tipos de contrato
        $tiposContratos = TipoContrato::all();

        // Pasar los tipos de contrato a la vista
        return view('admin.configuracion.tipos_contratos.edit', compact('tiposContratos', 'tipoContrato'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoContrato $tipoContrato)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255', // Validación del nombre
        ]);

        // Actualización del tipo de contrato
        $tipoContrato->update($validated);

        return redirect()->route('admin.configuracion.tipos-contratos.index')->with('success', 'Tipo de contrato actualizado exitosamente');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoContrato $tipoContrato)
    {
        $tipoContrato->delete(); // Eliminar tipo de contrato

        return redirect()->route('admin.configuracion.tipos-contratos.index')->with('success', 'Tipo de contrato eliminado exitosamente');
    }
}
