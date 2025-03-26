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
                            <th class="py-2 px-4 text-left">Comentario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vacacionesPropias as $vacacion)
                            <tr class="bg-white border-b">
                                <td class="py-2 px-4">{{ $vacacion->fecha_inicio }}</td>
                                <td class="py-2 px-4">{{ $vacacion->fecha_fin }}</td>
                                <td class="py-2 px-4">{{ $vacacion->duracion_dias }}</td>
                                <td class="py-2 px-4">
                                    @if($vacacion->estado == 'aprobadas')
                                        <span class="bg-green-500 text-white px-4 py-2 rounded-lg">{{ $vacacion->estado }}</span>
                                    @elseif($vacacion->estado == 'pendiente')
                                        <span class="bg-yellow-500 text-black px-4 py-2 rounded-lg">{{ $vacacion->estado }}</span>
                                    @elseif($vacacion->estado == 'rechazadas')
                                        <span class="bg-red-500 text-white px-4 py-2 rounded-lg">{{ $vacacion->estado }}</span>
                                    @elseif($vacacion->estado == 'pendientes_aprobacion')
                                        <span class="bg-blue-500 text-white px-4 py-2 rounded-lg">{{ $vacacion->estado }}</span>
                                    @else
                                        <span class="bg-gray-500 text-white px-4 py-2 rounded-lg">{{ $vacacion->estado }}</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 text-gray-700">
                                    {{ $vacacion->comentario ?? 'Sin comentario' }}
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