<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;

class EmpleadoEliminarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Inicializamos la consulta
        $query = Empleado::query();

        // Filtrar por nombre si se ha enviado el filtro 'nombre'
        if ($request->has('nombre') && !empty($request->nombre)) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        // Obtener los empleados que serán mostrados
        $empleados = $query->get();

        // Retornar la vista con los empleados y los filtros
        return view('configuracion.eliminar-empleado.index', [
            'empleados' => $empleados,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Buscar el empleado por su id
        $empleado = Empleado::findOrFail($id);

        // Eliminar el empleado
        $empleado->delete();

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->route('configuracion.eliminar-empleado.index')->with('success', 'Empleado eliminado exitosamente.');
    }
}
