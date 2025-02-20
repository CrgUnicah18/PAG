<?php

namespace App\Http\Controllers;
use App\Models\Vacacion;

use Illuminate\Http\Request;

class VacacionController extends Controller
{
    // Crear una nueva vacación
    public function store(Request $request)
    {
        $request->validate([
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        Vacacion::create([
            'empleado_id' => $request->empleado_id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'estado' => 'Pendiente',  // o lo que definas
        ]);

        return redirect()->route('vacaciones.index')->with('success', 'Vacación creada exitosamente.');
    }
}
