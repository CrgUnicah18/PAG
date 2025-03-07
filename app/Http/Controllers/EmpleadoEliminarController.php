<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;

class EmpleadoEliminarController extends Controller
{
    public function index(Request $request)
    {
        // Obtener los empleados filtrados por nombre y estado
        $empleados = Empleado::when($request->nombre, function ($query) use ($request) {
            return $query->where('nombre', 'like', '%' . $request->nombre . '%');
        })
            ->when($request->estado, function ($query) use ($request) {
                // Filtrar por estado (activo, terminado, inactivo)
                return $query->where('estado', $request->estado);
            })
            ->get();

        return view('admin.configuracion.eliminar-empleado.index', compact('empleados'));
    }

    public function destroy($id)
    {
        // Encontramos al empleado
        $empleado = Empleado::findOrFail($id);

        // Cambiamos el estado del empleado a "terminado"
        $empleado->estado = 'terminado';
        $empleado->save();

        // Redirigimos a la vista con el mensaje de éxito
        return redirect()->route('admin.configuracion.eliminar-empleado.index')
            ->with('success', 'Empleado marcado como terminado exitosamente.');
    }
    /*public function index(Request $request)
    {

        // Inicializamos la consulta
        $query = Empleado::query();


        // Filtrar por nombre si se ha enviado el filtro 'nombre'
        if ($request->has('nombre') && !empty($request->nombre)) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        // Obtener los empleados que serán mostrados
        $empleados = $query->get();
        dd($empleados);
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
}
