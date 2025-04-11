@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h1 class="text-center mb-4 text-gray-800 text-2xl font-semibold">Mis Solicitudes de Vacaciones</h1>

            <!-- Botón para Solicitar Vacaciones -->
            <a href="{{ route('empleado.vacaciones.create') }}"
                class="btn btn-primary mb-3 text-white px-6 py-2 rounded-full bg-blue-600 hover:bg-blue-700">Solicitar
                Vacaciones</a>

            <!-- Tabla de Solicitudes -->
            <div class="overflow-x-auto">
                <table class="min-w-full table-auto border-separate border-spacing-2">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="py-2 px-4 text-left">Fecha Inicio</th>
                            <th class="py-2 px-4 text-left">Fecha Fin</th>
                            <th class="py-2 px-4 text-left">Duración (días)</th>
                            <th class="py-2 px-4 text-left">Estado</th>
                            <th class="py-2 px-4 text-left">Reintegro</th>
                            <th class="py-2 px-4 text-left">Periodo</th>
                            <th class="py-2 px-4 text-left">Comentario</th>
                            <th class="py-2 px-4 text-left">Formato</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vacacionesPropias as $vacacion)
                            <tr class="bg-white border-b whitespace-nowrap">
                                <td class="py-2 px-4">{{ $vacacion->fecha_inicio }}</td>
                                <td class="py-2 px-4">{{ $vacacion->fecha_fin }}</td>
                                <td class="py-2 px-4">{{ $vacacion->duracion_dias }}</td>
                                <td class="py-2 px-4">
                                    @if($vacacion->estado == 'aprobadas')
                                        <span class="bg-green-500 text-white px-4 py-2 rounded-lg">{{ $vacacion->estado }}</span>
                                    @elseif($vacacion->estado == 'pendiente')
                                        <span class="bg-yellow-500 text-white px-4 py-2 rounded-lg">{{ $vacacion->estado }}</span>
                                    @elseif($vacacion->estado == 'rechazadas')
                                        <span class="bg-red-500 text-white px-4 py-2 rounded-lg">{{ $vacacion->estado }}</span>
                                    @elseif($vacacion->estado == 'pendientes_aprobacion')
                                        <span class="bg-blue-500 text-white px-4 py-2 rounded-lg">{{ $vacacion->estado }}</span>
                                    @else
                                        <span class="bg-gray-500 text-white px-4 py-2 rounded-lg">{{ $vacacion->estado }}</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4">{{ $vacacion->reintegro }}</td>
                                <td class="py-2 px-4">{{ $vacacion->periodo }} - {{ $vacacion->periodo + 1 }}</td>
                                <td class="py-2 px-4 text-gray-700">
                                    {{ $vacacion->comentario ?? 'Sin comentario' }}
                                </td>
                                <td class="py-2 px-4 text-gray-700">
                                    @if($vacacion->estado === 'aprobadas')
                                        <!-- Botón para descargar el formato de vacacion -->
                                        <a href="{{ route('empleado.vacacion.formato', $vacacion->id) }}"
                                            class="bg-green-500 text-white hover:bg-green-400 rounded-lg px-3 py-1 text-xs mt-2">
                                            <i class="fas fa-download text-sm"></i>
                                        </a>
                                    @else
                                        <!-- Botón deshabilitado si no está aprobado -->
                                        <button class="bg-gray-400 text-white rounded-lg px-3 py-1 text-xs mt-2" disabled>
                                            <i class="fas fa-download text-sm"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Enlaces de paginación -->
            <div class="pagination mt-4">
                {{ $vacacionesPropias->links() }}
            </div>
        </div>
    </div>
@endsection