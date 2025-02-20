<?php

namespace App\Http\Controllers;
use App\Models\Empleado;
use App\Models\Oficina;
use App\Models\Grupo;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
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

        // Filtrar por grupo si se ha enviado el filtro 'grupo_id'
        if ($request->has('grupo_id') && !empty($request->grupo_id)) {
            $query->where('grupo_id', $request->grupo_id);
        }

        // Filtrar por oficina si se ha enviado el filtro 'oficina_id'
        if ($request->has('oficina_id') && !empty($request->oficina_id)) {
            $query->where('oficina_id', $request->oficina_id);
        }

        // Obtener los empleados con la relación supervisor cargada
        $empleados = $query->with('supervisor')->get();

        // Obtener todos los grupos y oficinas para el filtro
        $grupos = Grupo::all();
        $oficinas = Oficina::all();

        // Retornar la vista con los empleados y los filtros
        return view('empleados.index', [
            'empleados' => $empleados,
            'grupos' => $grupos,
            'oficinas' => $oficinas,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener todos los empleados que no tengan un supervisor asignado
        $supervisores = Empleado::whereNull('supervisor_id')->get();

        return view('empleados.create', [
            'oficinas' => Oficina::all(),
            'grupos' => Grupo::all(),
            'supervisores' => $supervisores, // Pasar los empleados disponibles como supervisores
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de los datos recibidos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'fecha_nacimiento' => 'required|date',
            'oficina_id' => 'required|exists:oficinas,id',
            'grupo_id' => 'required|exists:grupos,id',
            'supervisor_id' => 'nullable|exists:empleados,id', // Permite supervisor nulo
        ]);

        // Creación del nuevo empleado
        $empleado = new Empleado();
        $empleado->nombre = $validated['nombre'];
        $empleado->apellido = $validated['apellido'];
        $empleado->direccion = $validated['direccion'];
        $empleado->telefono = $validated['telefono'];
        $empleado->fecha_nacimiento = $validated['fecha_nacimiento'];
        $empleado->oficina_id = $validated['oficina_id'];
        $empleado->grupo_id = $validated['grupo_id'];
        $empleado->supervisor_id = $validated['supervisor_id']; // Puede ser null
        $empleado->save();

        // Redirección con mensaje de éxito
        return redirect()->route('empleados.index')->with('success', 'Empleado creado exitosamente');
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
    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        $oficinas = Oficina::all();
        $grupos = Grupo::all();

        // Obtener todos los empleados que no tengan un supervisor asignado
        $supervisores = Empleado::whereNull('supervisor_id')->get();

        return view('empleados.edit', compact('empleado', 'oficinas', 'grupos', 'supervisores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validar los datos
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|min:8|max:15',
            'grupo_id' => 'required|exists:grupos,id',
            'oficina_id' => 'required|exists:oficinas,id',
            'supervisor_id' => 'nullable|exists:empleados,id', // supervisor puede ser nulo
            'estado' => 'required|in:activo,inactivo', // Asegurarse de que el estado sea válido
        ]);

        // Buscar el empleado y actualizar sus datos
        $empleado = Empleado::findOrFail($id);
        $empleado->update($validatedData);

        // Redirigir con un mensaje de éxito
        return redirect()->route('empleados.index')->with('success', 'Empleado actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Buscar el empleado por su id
        $empleado = Empleado::findOrFail($id);

        // Eliminar el empleado
        $empleado->delete();

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->route('empleados.index')->with('success', 'Empleado eliminado exitosamente.');
    }
}
