<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Models\Oficina;
use App\Models\Grupo;
use App\Models\TipoContrato; // Asegúrate de importar el modelo TipoContrato
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index(Request $request)
    {
        $query = Empleado::query();

        if ($request->has('nombre') && !empty($request->nombre)) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }

        if ($request->has('grupo_id') && !empty($request->grupo_id)) {
            $query->where('grupo_id', $request->grupo_id);
        }

        if ($request->has('oficina_id') && !empty($request->oficina_id)) {
            $query->where('oficina_id', $request->oficina_id);
        }
        // Filtro por estado
        if ($request->has('estado') && !empty($request->estado)) {
            $query->where('estado', $request->estado);
        }

        $empleados = $query->with('supervisor')->get();
        $grupos = Grupo::all();
        $oficinas = Oficina::all();
        $tiposContratos = TipoContrato::all(); // Obtener todos los tipos de contrato

        return view('admin.empleados.index', [
            'empleados' => $empleados,
            'grupos' => $grupos,
            'oficinas' => $oficinas,
            'tiposContratos' => $tiposContratos, // Pasar los tipos de contrato a la vista
        ]);
    }

    public function create()
    {
        $supervisores = Empleado::whereNull('supervisor_id')->get();
        $oficinas = Oficina::all();
        $grupos = Grupo::all();
        $tiposContratos = TipoContrato::all(); // Obtener todos los tipos de contrato

        return view('admin.empleados.create', [
            'oficinas' => $oficinas,
            'grupos' => $grupos,
            'supervisores' => $supervisores,
            'tiposContratos' => $tiposContratos, // Pasar los tipos de contrato a la vista
        ]);
    }

    public function show($id)
    {
        $empleado = Empleado::findOrFail($id);
        $oficinas = Oficina::all();
        $grupos = Grupo::all();
        $tiposContratos = TipoContrato::all();

        return view('admin.empleados.show', [
            'empleado' => $empleado,
            'oficinas' => $oficinas,
            'grupos' => $grupos,
            'tiposContratos' => $tiposContratos,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'fecha_nacimiento' => 'required|date',
            'fecha_ingreso' => 'required|date',
            'oficina_id' => 'required|exists:oficinas,id',
            'grupo_id' => 'required|exists:grupos,id',
            'supervisor_id' => 'nullable|exists:empleados,id',
            'tipo_contrato_id' => 'required|exists:tipo_contratos,id',
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Validación de la foto
            'documento_contrato' => 'nullable|file|mimes:pdf,docx,txt|max:5000',  // Validación del contrato
        ]);

        $empleado = new Empleado();
        $empleado->nombre = $validated['nombre'];
        $empleado->apellido = $validated['apellido'];
        $empleado->direccion = $validated['direccion'];
        $empleado->telefono = $validated['telefono'];
        $empleado->fecha_nacimiento = $validated['fecha_nacimiento'];
        $empleado->fecha_ingreso = $validated['fecha_ingreso'];
        $empleado->oficina_id = $validated['oficina_id'];
        $empleado->grupo_id = $validated['grupo_id'];
        $empleado->supervisor_id = $validated['supervisor_id'];
        $empleado->tipo_contrato_id = $validated['tipo_contrato_id'];

        // Subir foto de perfil
        if ($request->hasFile('foto_perfil')) {
            $imageName = time() . '-' . $request->file('foto_perfil')->getClientOriginalName();
            $request->file('foto_perfil')->move(public_path('empleados/img'), $imageName);
            $empleado->foto_perfil = 'empleados/img/' . $imageName;  // Guardar la ruta relativa
        }

        // Subir contrato
        if ($request->hasFile('documento_contrato')) {
            $documentName = time() . '-' . $request->file('documento_contrato')->getClientOriginalName();
            $request->file('documento_contrato')->move(public_path('empleados/img_contratos'), $documentName);
            $empleado->documento_contrato = 'empleados/img_contratos/' . $documentName;  // Guardar la ruta relativa
        }

        $empleado->save();

        return redirect()->route('admin.empleados.index')->with('success', 'Empleado creado exitosamente');
    }


    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        $oficinas = Oficina::all();
        $grupos = Grupo::all();
        $supervisores = Empleado::whereNull('supervisor_id')->get();
        $tiposContratos = TipoContrato::all();

        return view('admin.empleados.edit', compact('empleado', 'oficinas', 'grupos', 'supervisores', 'tiposContratos'));
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        // Validación de los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'grupo_id' => 'required|exists:grupos,id',
            'oficina_id' => 'required|exists:oficinas,id',
            'supervisor_id' => 'nullable|exists:empleados,id',
            'estado' => 'required|in:activo,inactivo',
            'tipo_contrato_id' => 'required|exists:tipo_contratos,id',
            'fecha_ingreso' => 'required|date',
            'foto_peril' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'documento_contrato' => 'nullable|mimes:pdf,doc,docx,zip|max:2048', // Solo PDF, DOC, DOCX, ZIP
        ]);

        // Actualización de los datos del empleado
        $empleado->nombre = $request->nombre;
        $empleado->apellido = $request->apellido;
        $empleado->telefono = $request->telefono;
        $empleado->grupo_id = $request->grupo_id;
        $empleado->oficina_id = $request->oficina_id;
        $empleado->supervisor_id = $request->supervisor_id;
        $empleado->estado = $request->estado;
        $empleado->tipo_contrato_id = $request->tipo_contrato_id;
        $empleado->fecha_ingreso = $request->fecha_ingreso;

        // Manejo de la imagen de perfil
        if ($request->hasFile('foto_perfil')) { // ← Corrección aquí
            $imageName = time() . '-' . $request->file('foto_perfil')->getClientOriginalName();
            $request->file('foto_perfil')->move(public_path('empleados/img'), $imageName);
            $empleado->foto_perfil = 'empleados/img/' . $imageName; // ← Corrección aquí
        }

        // Manejo de la imagen del contrato
        if ($request->hasFile('documento_contrato')) {
            $documentName = time() . '-' . $request->file('documento_contrato')->getClientOriginalName();
            $request->file('documento_contrato')->move(public_path('empleados/img_contratos'), $documentName);
            $empleado->documento_contrato = 'empleados/img_contratos/' . $documentName;
        }


        // Guardar los cambios
        $empleado->save();


        return redirect()->route('admin.empleados.index')->with('success', 'Empleado actualizado correctamente');
    }

}
