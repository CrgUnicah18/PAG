<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use App\Models\Reaccion;
use App\Models\Oficina;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnuncioController extends Controller
{
    public function index(Request $request)
    {
        $usuario = auth()->user();
        $empleado = $usuario->empleado;

        // Si es admin, muestra todo sin filtros
        if ($usuario->hasRole('admin')) {
            // Filtros por prioridad
            $query = Anuncio::with(['oficinas', 'grupos', 'reactions.empleado'])->latest();

            if ($request->has('prioridad') && $request->prioridad != '') {
                $query->where('prioridad', $request->prioridad);
            }

            // Filtrar por anuncios no expirados
            $query->where(function ($q) {
                $q->whereNull('fecha_expiracion')
                    ->orWhere('fecha_expiracion', '>', Carbon::now());
            });

            $anuncios = $query->paginate(5); // Paginación de 5 anuncios por página

            // Contar las reacciones para cada anuncio
            foreach ($anuncios as $anuncio) {
                $anuncio->conteo_reacciones = $anuncio->reactions->count();
                $anuncio->is_new = Carbon::parse($anuncio->fecha_hora)->greaterThan(Carbon::now()->subDays(3)); // Marcar como "Nuevo" si el anuncio tiene menos de 3 días
            }

            return view('admin.anuncios.index', compact('anuncios'));
        }

        // Si no es admin, se filtra según la audiencia seleccionada
        $oficinaId = $empleado->oficina->id ?? null;
        $grupoId = $empleado->grupo->id ?? null;

        // Filtrar anuncios según la audiencia
        $anuncios = Anuncio::where(function ($query) use ($oficinaId, $grupoId) {
            $query->where('audiencia', 'empresa') // Mostrar a todos los empleados si la audiencia es 'empresa'
                ->orWhere(function ($q) use ($oficinaId) {
                    $q->where('audiencia', 'oficina')
                        ->whereHas('oficinas', function ($q) use ($oficinaId) {
                            $q->whereIn('oficinas.id', (array) $oficinaId); // Permite varias oficinas
                        });
                })
                ->orWhere(function ($q) use ($grupoId) {
                    $q->where('audiencia', 'grupo')
                        ->whereHas('grupos', function ($q) use ($grupoId) {
                            $q->whereIn('grupos.id', (array) $grupoId); // Permite varios grupos
                        });
                })
                ->orWhere(function ($q) use ($oficinaId, $grupoId) {
                    $q->where('audiencia', 'todos')
                        ->whereHas('oficinas', function ($q) use ($oficinaId) {
                            $q->whereIn('oficinas.id', (array) $oficinaId); // Filtra por oficinas
                        })
                        ->whereHas('grupos', function ($q) use ($grupoId) {
                            $q->whereIn('grupos.id', (array) $grupoId); // Filtra por grupos
                        });
                });
        })
            ->where(function ($q) {
                // Filtrar por anuncios no expirados (igual que en el admin)
                $q->whereNull('fecha_expiracion')
                    ->orWhere('fecha_expiracion', '>', Carbon::now());
            })
            ->with('reactions') // Asegúrate de cargar las reacciones aquí
            ->latest()
            ->paginate(5); // Paginación de 5 anuncios por página

        // Vistas según el rol
        if ($usuario->hasRole('supervisor')) {
            return view('supervisor.anuncios.index', compact('anuncios'));
        }

        return view('empleado.anuncios.index', compact('anuncios'));
    }


    public function create()
    {
        // Obtener todas las oficinas y grupos disponibles
        $oficinas = Oficina::all();
        $grupos = Grupo::all();

        return view('admin.anuncios.create', compact('oficinas', 'grupos'));
    }


    public function store(Request $request)
    {
        // Validación de los campos
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'audiencia' => 'required|string',
            'fecha_expiracion' => 'required|date',
            'prioridad' => 'required|string',
        ]);

        // Crear el anuncio
        $anuncio = new Anuncio();
        $anuncio->titulo = $request->titulo;
        $anuncio->contenido = $request->contenido;
        $anuncio->audiencia = $request->audiencia;
        $anuncio->fecha_hora = now(); // Fecha y hora actual
        $anuncio->fecha_expiracion = $request->fecha_expiracion;
        $anuncio->prioridad = $request->prioridad;

        // Guardar los grupos seleccionados (si hay)
        if ($request->audiencia === 'grupo' || $request->audiencia === 'todos') {
            $anuncio->grupos()->sync($request->grupos);
        }

        // Guardar las oficinas seleccionadas (si hay)
        if ($request->audiencia === 'oficina' || $request->audiencia === 'todos') {
            $anuncio->oficinas()->sync($request->oficinas);
        }

        // Guardar el anuncio
        $anuncio->save();

        return redirect()->route('admin.anuncios.index')->with('success', 'Anuncio creado con éxito.');
    }


    public function destroy(Anuncio $anuncio)
    {
        // Eliminar el anuncio
        $anuncio->delete();

        // Redirigir con mensaje de éxito
        return redirect()->route('admin.anuncios.index')->with('success', 'Anuncio eliminado exitosamente');
    }

    public function edit(Anuncio $anuncio)
    {
        $oficinas = Oficina::all();
        $grupos = Grupo::all();

        return view('admin.anuncios.edit', compact('anuncio', 'oficinas', 'grupos'));
    }

    public function update(Request $request, Anuncio $anuncio)
    {
        // Validar los datos del formulario
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'audiencia' => 'required|in:empresa,todos,oficina,grupo',
            'fecha_hora' => 'nullable|date',
            'prioridad' => 'required|in:alta,media,baja',
            'fecha_expiracion' => 'nullable|date|after:fecha_hora', // Validar fecha de expiración
            'oficinas' => 'nullable|array|exists:oficinas,id', // Validar existencia de oficinas
            'grupos' => 'nullable|array|exists:grupos,id', // Validar existencia de grupos
        ]);

        // Actualizar el anuncio
        $anuncio->update([
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'audiencia' => $request->audiencia,
            'fecha_hora' => $request->fecha_hora,
            'fecha_expiracion' => $request->fecha_expiracion, // Actualizar fecha de expiración
            'prioridad' => $request->prioridad,
        ]);

        // Sincronizar oficinas si la audiencia es oficina, todos o empresa
        if (in_array($request->audiencia, ['oficina', 'todos'])) {
            if ($request->has('oficinas')) {
                // Sincronizar las oficinas seleccionadas con el anuncio
                $anuncio->oficinas()->sync($request->oficinas);
            }
        }

        // Sincronizar grupos si la audiencia es grupo, todos o empresa
        if (in_array($request->audiencia, ['grupo', 'todos'])) {
            if ($request->has('grupos')) {
                // Sincronizar los grupos seleccionados con el anuncio
                $anuncio->grupos()->sync($request->grupos);
            }
        }

        // Redirigir con mensaje de éxito
        return redirect()->route('admin.anuncios.index')->with('success', 'Anuncio actualizado exitosamente');
    }
}
