<?php

namespace App\Http\Controllers;

use App\Models\Anuncio;
use App\Models\Reaccion; // Asegúrate de que el nombre del modelo esté correcto
use App\Models\Oficina;
use App\Models\Grupo;
use Illuminate\Http\Request;

class AnuncioController extends Controller
{
    public function index()
    {
        $usuario = auth()->user();
        $empleado = $usuario->empleado;

        // Si es admin, muestra todo sin filtros
        if ($usuario->hasRole('admin')) {
            $anuncios = Anuncio::with('oficinas', 'grupos', 'reactions')
                ->latest()
                ->paginate(5); // Paginación de 5 anuncios por página

            // Contar las reacciones para cada anuncio
            foreach ($anuncios as $anuncio) {
                $anuncio->conteo_reacciones = $anuncio->reactions->count();
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
            ->with('reactions') // Asegúrate de cargar las reacciones aquí
            ->latest()
            ->paginate(5); // Paginación de 5 anuncios por página

        // Aquí ya no filtramos los anuncios que el empleado ha visto, ya que no queremos eliminarlos del listado

        // Vistas según el rol
        if ($usuario->hasRole('supervisor')) {
            return view('supervisor.anuncios.index', compact('anuncios'));
        }

        return view('empleado.anuncios.index', compact('anuncios'));
    }

    public function reactToAnuncio(Anuncio $anuncio)
    {
        $usuario = auth()->user();
        $empleado = $usuario->empleado;

        // Verificar si el empleado ya reaccionó
        $reaccion = Reaccion::firstOrCreate(
            ['empleado_id' => $empleado->id, 'anuncio_id' => $anuncio->id],
            ['visto' => true] // Si no existía, se marca como visto
        );

        // Si ya existía, se actualiza el campo visto
        if (!$reaccion->wasRecentlyCreated) {
            $reaccion->update(['visto' => true]);
        }

        // Validación con hasRole() para comprobar si es supervisor o empleado
        if ($usuario->hasRole('supervisor')) {
            // Si el usuario es supervisor, redirigir al panel de supervisores
            return redirect()->route('supervisor.anuncios.index')->with('success', 'Has marcado este anuncio como visto.');
        } else {
            // Si el usuario es empleado, redirigir al panel de empleados
            return redirect()->route('empleado.anuncios.index')->with('success', 'Has marcado este anuncio como visto.');
        }
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
        // Validar los datos del formulario
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'audiencia' => 'required|in:empresa,todos,oficina,grupo',
            'fecha_hora' => 'nullable|date',
            'prioridad' => 'required|in:alta,media,baja',
            'oficinas' => 'nullable|array|exists:oficinas,id', // Validar existencia de oficinas
            'grupos' => 'nullable|array|exists:grupos,id',     // Validar existencia de grupos
        ]);

        // Crear el anuncio
        $anuncio = Anuncio::create([
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'audiencia' => $request->audiencia,
            'fecha_hora' => $request->fecha_hora,
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
        return redirect()->route('admin.anuncios.index')->with('success', 'Anuncio creado exitosamente');
    }

    public function destroy(Anuncio $anuncio)
    {
        // Eliminar el anuncio
        $anuncio->delete();

        // Redirigir con mensaje de éxito
        return redirect()->route('admin.anuncios.index')->with('success', 'Anuncio eliminado exitosamente');
    }

}
