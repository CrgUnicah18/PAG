@extends('layouts.app')

@section('content')
    <div class="container mt-8">

        {{-- Mensajes de éxito o error --}}
        @if(session('success'))
            <div class="alert alert-success mb-6 p-4 rounded-lg bg-green-100 border border-green-400 text-green-700">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger mb-6 p-4 rounded-lg bg-red-100 border border-red-400 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Título centrado y con estilo --}}
        <div class="text-center mb-6">
            <h2 class="text-4xl font-bold text-gray-800 border-b-4 border-blue-600 inline-block pb-2">
                Mis Solicitudes de Permiso
            </h2>
        </div>

        {{-- Botones con íconos --}}
        <div class="flex justify-center gap-4 mb-6">
            <a href="{{ route('empleado.permisos.lista') }}"
                class="inline-flex items-center gap-2 bg-gray-700 hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-lg shadow-md transition">
                <i class="fas fa-list w-5 h-5"></i>
                Ver tipos de permisos
            </a>

            <a href="{{ route('empleado.permisos.create') }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-md transition"
                style="background-color: rgb(231, 173, 33); color: white; padding: 10px 20px; border: none; border-radius: 8px;">
                <i class="fas fa-plus-circle w-5 h-5"></i>
                Solicitar Permiso
            </a>
        </div>

        {{-- Tabla de permisos --}}
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full text-sm text-gray-700">
                <thead style="background-color: rgb(36, 94, 167);">
                    <tr>
                        <th class="px-4 py-3 text-left text-white">Empleado</th>
                        <th class="px-4 py-3 text-left text-white">Tipo de Permiso</th>
                        <th class="px-4 py-3 text-left text-white">Fecha de Inicio</th>
                        <th class="px-4 py-3 text-left text-white">Fecha de Fin</th>
                        <th class="px-4 py-3 text-left text-white">Días</th>
                        <th class="px-4 py-3 text-left text-white">Reintegro</th>
                        <th class="px-4 py-3 text-left text-white">Estado</th>
                        <th class="px-4 py-3 text-left text-white">Comentario</th>
                        <th class="px-4 py-3 text-center text-white">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($permisosEmpleado as $permiso)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">{{ $permiso->empleado->nombre }} {{ $permiso->empleado->apellido }}</td>
                            <td class="px-4 py-3">{{ $permiso->tipoPermiso->nombre }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($permiso->fecha_inicio)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($permiso->fecha_fin)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ $permiso->dias_laborables }}</td>
                            <td class="px-4 py-3 whitespace-nowrap">{{ $permiso->reintegro }}</td>

                            <td class="px-4 py-3">
                                @if($permiso->estado == 'pendiente')
                                    <span
                                        class="inline-block px-3 py-1 text-xs font-semibold bg-yellow-400 text-white rounded">Pendiente</span>
                                @elseif($permiso->estado == 'pendiente_aprobacion')
                                    <span class="inline-block px-3 py-1 text-xs font-semibold bg-blue-500 text-white rounded">Pend.
                                        Aprobación</span>
                                @elseif($permiso->estado == 'aprobado')
                                    <span
                                        class="inline-block px-3 py-1 text-xs font-semibold bg-green-500 text-white rounded">Aprobado</span>
                                @elseif($permiso->estado == 'rechazado')
                                    <span
                                        class="inline-block px-3 py-1 text-xs font-semibold bg-red-500 text-white rounded">Rechazado</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($permiso->comentario)
                                    <div class="comment-container">
                                        @foreach(explode("\n", $permiso->comentario) as $line)
                                            <p class="comment-line">{{ $line }}</p>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Sin comentario</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button data-bs-toggle="modal" data-bs-target="#commentModal{{ $permiso->id }}"
                                    class="bg-yellow-500 text-white hover:bg-yellow-400 rounded-lg px-3 py-1 text-xs">
                                    <i class="fas fa-comment-dots text-xl"></i> Comentar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Modales de comentario --}}
        @foreach($permisosEmpleado as $permiso)
            <div class="modal fade" id="commentModal{{ $permiso->id }}" tabindex="-1"
                aria-labelledby="commentModalLabel{{ $permiso->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="commentModalLabel{{ $permiso->id }}">Agregar Comentario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('empleado.permisos.comentar', $permiso->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="comentario" class="form-label">Comentario</label>
                                    <textarea class="form-control" id="comentario" name="comentario" rows="3"
                                        required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Guardar Comentario</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach
@endsection