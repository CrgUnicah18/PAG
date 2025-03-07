<?php

// app/Http/Controllers/GrupoController.php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Oficina;
use Illuminate\Http\Request;

class GrupoController extends Controller
{

    public function index()
    {
        $grupos = Grupo::all();  // Obtener todos los grupos
        return view('admin.configuracion.crear_grupo.index', compact('grupos'));
    }

    // Mostrar el formulario para crear un nuevo grupo
    public function create()
    {
        $oficinas = Oficina::all();  // Obtener todas las oficinas para el formulario de creación de grupo
        return view('admin.configuracion.crear_grupo.create', compact('oficinas')); // Vista de formulario de creación de grupo
    }

    // Almacenar un nuevo grupo
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'oficina_id' => 'required|exists:oficinas,id', // Aseguramos que la oficina exista en la base de datos
        ]);

        // Crear el grupo
        Grupo::create([
            'nombre' => $request->nombre,
            'oficina_id' => $request->oficina_id,
        ]);

        // Redirigir a la lista de grupos con un mensaje de éxito
        return redirect()->route('admin.configuracion.crear_grupo.index')->with('success', 'Grupo creado con éxito');
    }
}
