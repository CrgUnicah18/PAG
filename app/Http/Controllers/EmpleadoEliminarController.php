<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Grupo;
use App\Models\Oficina;

class EmpleadoEliminarController extends Controller
{
    public function index(Request $request)
    {

        $grupos = Grupo::all();
        $oficinas = Oficina::all();
        // Obtener los empleados filtrados por nombre y estado

        $empleados = Empleado::when($request->nombre, function ($query) use ($request) {
            return $query->where('nombre', 'like', '%' . $request->nombre . '%');
        })
            ->when($request->estado, function ($query) use ($request) {
                // Filtrar por estado (activo, terminado, inactivo)
                return $query->where('estado', $request->estado);
            })
            ->when($request->grupo_id, function ($query) use ($request) {
                return $query->where('grupo_id', $request->grupo_id);
            })
            ->when($request->oficina_id, function ($query) use ($request) {
                return $query->where('oficina_id', $request->oficina_id);
            })
            ->get();

        return view('admin.configuracion.eliminar-empleado.index', compact('empleados', 'grupos', 'oficinas'));
    }
    public function recontratar($id)
    {
        // Buscar el empleado por ID
        $empleado = Empleado::findOrFail($id);

        // Verificar que el estado sea "terminado" antes de cambiarlo
        if ($empleado->estado === 'terminado') {
            $empleado->estado = 'activo';
            $empleado->save();

            return redirect()->route('admin.configuracion.eliminar-empleado.index')
                ->with('success', 'Empleado recontratado exitosamente.');
        }

        return redirect()->route('admin.configuracion.eliminar-empleado.index')
            ->with('error', 'El empleado no puede ser recontratado.');
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
