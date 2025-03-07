@extends('layouts.app')

@section('content')
    <div class="container mt-6">

        <!-- Mensajes de éxito o error -->
        @if(session('success'))
            <div class="alert alert-success mb-4 p-4 rounded-md bg-green-100 border border-green-400 text-green-700">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger mb-4 p-4 rounded-md bg-red-100 border border-red-400 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <!-- Título de la sección -->
        <div class="mb-6">
            <h2 class="text-3xl font-semibold text-gray-800 border-b-2 border-gray-300 pb-2">
                Mis Solicitudes de Permiso
            </h2>
        </div>

        <!-- Tabla de permisos -->
        <div class="overflow-x-auto shadow-lg rounded-lg bg-white">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left text-sm text-gray-600">Empleado</th>
                        <th class="px-4 py-2 text-left text-sm text-gray-600">Tipo de Permiso</th>
                        <th class="px-4 py-2 text-left text-sm text-gray-600">Fecha de Inicio</th>
                        <th class="px-4 py-2 text-left text-sm text-gray-600">Fecha de Fin</th>
                        <th class="px-4 py-2 text-left text-sm text-gray-600">Estado</th>
                        <th class="px-4 py-2 text-left text-sm text-gray-600">Comentario</th>
                        <th class="px-4 py-2 text-center text-sm text-gray-600">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permisosEmpleado as $permiso)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $permiso->empleado->nombre }}
                                {{ $permiso->empleado->apellido }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $permiso->tipoPermiso->nombre }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($permiso->fecha_inicio)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($permiso->fecha_fin)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-sm text-gray-700">
                                @if($permiso->estado == 'pendiente')
                                    <span class="badge bg-yellow-400 text-white px-2 py-1 rounded">Pendiente</span>
                                @elseif($permiso->estado == 'pendiente_aprobacion')
                                    <span class="badge bg-blue-500 text-white px-2 py-1 rounded">Pendiente de Aprobación</span>
                                @elseif($permiso->estado == 'aprobado')
                                    <span class="badge bg-green-500 text-white px-2 py-1 rounded">Aprobado</span>
                                @elseif($permiso->estado == 'rechazado')
                                    <span class="badge bg-red-500 text-white px-2 py-1 rounded">Rechazado</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-gray-700">
                                @if($permiso->comentario)
                                    {{ $permiso->comentario }}
                                @else
                                    <span class="text-gray-400">No hay comentario</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-center">
                                @if($permiso->estado == 'pendiente' || $permiso->estado == 'pendiente_aprobacion')
                                    <span class="text-gray-500">En espera de aprobación</span>
                                @else
                                    <span class="text-green-500">Procesado</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-4 text-center">
            {{ $permisosEmpleado->links() }}
        </div>

        <!-- Botón para crear un nuevo permiso -->
        <div class="text-center mt-4">
            <a href="{{ route('empleado.permisos.create') }}"
                class="btn btn-primary px-6 py-2 rounded-lg shadow-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                Solicitar Permiso
            </a>
        </div>
    </div>
@endsection