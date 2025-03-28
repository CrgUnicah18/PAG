@extends('layouts.app')

@section('content')
    <div class="container mt-8">

        <!-- Estilo para las vacaciones propias -->
        <div class="mb-6">
            <h2
                class="text-2xl font-semibold text-gray-800 flex justify-between items-center border-b-2 border-gray-300 pb-2">
                <span>🌴 Solicitud de Vacaciones</span>
                {{-- Botón para crear una nueva solicitud de vacaciones --}}
                <a href="{{ route('supervisor.vacaciones.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-xs"
                    style="background-color: rgb(231, 173, 33);">
                    Solicitar Vacaciones
                </a>


            </h2>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="bg-red-100 text-red-800 p-4 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tabla de vacaciones propias -->
        <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
            <table class="min-w-full table-auto border-collapse">
                <thead style="background-color: rgb(117, 178, 59);">
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-600 text-white">Empleado</th>
                        <th class="px-4 py-2 text-left text-gray-600 text-white">Fecha de Inicio</th>
                        <th class="px-4 py-2 text-left text-gray-600 text-white">Fecha de Fin</th>
                        <th class="px-4 py-2 text-left text-gray-600 text-white">Estado</th>
                        <th class="px-4 py-2 text-left text-gray-600 text-white">Comentario</th>
                        <th class="px-4 py-2 text-left text-gray-600 text-white">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vacacionesPropias as $vacacion)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $vacacion->empleado->nombre }} {{ $vacacion->empleado->apellido }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($vacacion->fecha_inicio)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($vacacion->fecha_fin)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2">
                                @if($vacacion->estado == 'pendientes_aprobacion')
                                    <span class="px-2 py-1 text-yellow-800 bg-yellow-200 rounded-full">Pendientes de
                                        Aprobación</span>
                                @elseif($vacacion->estado == 'aprobadas')
                                    <span class="px-2 py-1 text-green-800 bg-green-200 rounded-full">Aprobadas</span>
                                @elseif($vacacion->estado == 'rechazadas')
                                    <span class="px-2 py-1 text-red-800 bg-red-200 rounded-full">Rechazadas</span>
                                @elseif($vacacion->estado == 'pendiente')
                                    <span class="px-2 py-1 text-blue-800 bg-blue-200 rounded-full">Pendiente</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if($vacacion->comentario)
                                    {{ $vacacion->comentario }}
                                @else
                                    <span class="text-gray-400">No hay comentario</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                @if($vacacion->estado == 'pendientes_aprobacion')
                                    <span class="px-2 py-1 text-yellow-800 bg-yellow-200 rounded-full">Procesando</span>
                                @elseif($vacacion->estado == 'aprobadas' || $vacacion->estado == 'rechazadas')
                                    <span class="px-2 py-1 text-gray-800 bg-gray-200 rounded-full">Procesado</span>
                                @else
                                    <span class="px-2 py-1 text-blue-800 bg-blue-100 rounded-full">Solo admin puede aprobar</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            {{ $vacacionesPropias->appends(request()->query())->links() }}
        </div>

        <!-- Tabla de vacaciones generales (empleados a cargo) -->
        <div class="mt-8">
            <h4
                class="text-xl font-semibold text-gray-800 flex justify-between items-center border-b-2 border-gray-300 pb-2">
                Vacaciones de mis empleados
            </h4>

            <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
                <table class="min-w-full table-auto border-collapse">
                    <thead style="background-color: rgb(117, 178, 59);">
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-600 text-white rounded-tl-lg">Empleado</th>
                            <th class="px-4 py-2 text-left text-gray-600 text-white">Fecha de Inicio</th>
                            <th class="px-4 py-2 text-left text-gray-600 text-white">Fecha de Fin</th>
                            <th class="px-4 py-2 text-left text-gray-600 text-white">Estado</th>
                            <th class="px-4 py-2 text-left text-gray-600 text-white">Comentario</th>
                            <th class="px-4 py-2 text-left text-gray-600 w-32 text-white rounded-tr-lg">Acciones</th>
                            <!-- Ajusta el ancho de la columna -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vacacionesGenerales as $vacacion)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $vacacion->empleado->nombre }} {{ $vacacion->empleado->apellido }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($vacacion->fecha_inicio)->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($vacacion->fecha_fin)->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">
                                    @if($vacacion->estado == 'pendientes_aprobacion')
                                        <span class="px-2 py-1 text-xs text-blue-800 bg-blue-200 rounded-full">Pendientes</span>
                                    @elseif($vacacion->estado == 'aprobadas')
                                        <span class="px-2 py-1 text-xs text-green-800 bg-green-200 rounded-full">Aprobadas</span>
                                    @elseif($vacacion->estado == 'rechazadas')
                                        <span class="px-2 py-1 text-xs text-red-800 bg-red-200 rounded-full">Rechazadas</span>
                                    @elseif($vacacion->estado == 'pendiente')
                                        <span class="px-2 py-1 text-xs text-yellow-800 bg-yellow-200 rounded-full">Pendiente</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    @if($vacacion->comentario)
                                        {{ $vacacion->comentario }}
                                    @else
                                        <span class="text-gray-400">No hay comentario</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    @if($vacacion->estado == 'pendiente')
                                        <!-- Botones de acción con iconos para aprobar o rechazar vacaciones -->
                                        <div class="flex space-x-2">
                                            <form action="{{ route('supervisor.vacaciones.aprobar', $vacacion->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="p-2.5 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-200">
                                                    <i class="fas fa-check text-base"></i> <!-- Icono de aprobado -->
                                                </button>
                                            </form>

                                            <form action="{{ route('supervisor.vacaciones.declinar', $vacacion->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="p-2.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200">
                                                    <i class="fas fa-times text-base"></i> <!-- Icono de rechazo -->
                                                </button>
                                            </form>
                                        </div>

                                    @elseif($vacacion->estado == 'pendientes_aprobacion')
                                        <span class="px-2 py-1 text-xs text-blue-800 bg-blue-100 rounded-full">Esperando</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $vacacionesGenerales->appends(request()->query())->links() }}
            </div>

        </div>


    </div>
@endsection