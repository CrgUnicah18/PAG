@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-semibold text-gray-800">Solicitudes de Permisos</h2>
            <div class="flex space-x-6"> <!-- Espacio entre los botones sin separarlos tanto -->
                <a href="{{ route('admin.permisos.create') }}"
                    class="px-6 py-2 rounded-lg shadow-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none flex items-center">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    Solicitar Permiso
                </a>
                <a href="{{ route('admin.permisos.formulario') }}"
                    class="px-6 py-2 rounded-lg shadow-md text-white bg-red-600 hover:bg-blue-700 focus:outline-none flex items-center">
                    <i class="fas fa-file-alt mr-2"></i>
                    Reporte
                </a>
            </div>
        </div>


        {{-- Filtro por estado y nombre de empleado --}}
        <div class="mb-6">
            <form action="{{ route('admin.permisos.index') }}" method="GET"
                class="flex flex-col sm:flex-row sm:items-center sm:space-x-4 space-y-2 sm:space-y-0">

                {{-- Input de búsqueda por nombre --}}
                <input type="text" name="nombreEmpleado" value="{{ request('nombreEmpleado') }}"
                    class="w-full sm:w-64 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Buscar por nombre de empleado">

                {{-- Select de estado --}}
                <div class="flex items-center space-x-2">
                    <label for="estado" class="text-sm text-gray-700 font-medium">Estado:</label>
                    <select name="estado" id="estado"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Todos</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="pendiente_aprobacion" {{ request('estado') == 'pendiente_aprobacion' ? 'selected' : '' }}>
                            Pendiente de Aprobación</option>
                        <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                        <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>

                {{-- Botones de Filtrar y Limpiar --}}
                <div class="flex space-x-2">
                    <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2 rounded-lg transition-all duration-200">
                        Filtrar
                    </button>

                    <a href="{{ route('admin.permisos.index') }}"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-5 py-2 rounded-lg transition-all duration-200">
                        Limpiar
                    </a>
                </div>
            </form>
        </div>


        {{-- Tabla de todos los permisos --}}
        <h3 class="text-2xl font-semibold text-gray-800 mb-4">Permisos de Todos los Empleados</h3>
        <div class="overflow-x-auto shadow-md sm:rounded-lg mb-6">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-indigo-600 text-white text-center">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium">Empleado</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Tipo de Permiso</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Fechas</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Días</th> <!-- Nueva columna -->
                        <th class="px-6 py-3 text-left text-sm font-medium">Estado</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Comentario</th>
                        <th class="px-6 py-3 text-left text-sm font-medium">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permisos as $permiso)
                                    <tr class="border-b border-gray-200">
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $permiso->empleado->nombre }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $permiso->tipoPermiso->nombre }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $permiso->fecha_inicio }} - {{ $permiso->fecha_fin }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            @php
                                                $fechaInicio = \Carbon\Carbon::parse($permiso->fecha_inicio);
                                                $fechaFin = \Carbon\Carbon::parse($permiso->fecha_fin);

                                                // Si la fecha de inicio es el mismo día que la fecha de fin, y es un día laborable, se cuenta como 1 día laborable
                                                $diasLaborables = $fechaInicio->isWeekday() && $fechaInicio->equalTo($fechaFin) ? 1 : $fechaInicio->diffInWeekdays($fechaFin);
                                            @endphp
                                            {{ $diasLaborables }} <!-- Muestra los días laborables calculados -->
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            @if($permiso->estado == 'pendiente')
                                                <span class="px-2 py-1 rounded-full text-white bg-yellow-500">Pendiente</span>
                                            @elseif($permiso->estado == 'pendiente_aprobacion')
                                                <span class="px-2 py-1 rounded-full text-white bg-blue-500">Pendiente</span>
                                            @elseif($permiso->estado == 'aprobado')
                                                <span class="px-2 py-1 rounded-full text-white bg-green-500">Aprobado</span>
                                            @else
                                                <span class="px-2 py-1 rounded-full text-white bg-red-500">Rechazado</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            @if($permiso->comentario)
                                                <div class="comment-container">
                                                    {{-- Dividiendo los comentarios si es necesario para mayor claridad --}}
                                                    @foreach(explode("\n", $permiso->comentario) as $line)
                                                        <p class="comment-line">{{ $line }}</p>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic">Sin comentario</span>
                                            @endif

                                            {{-- Ver Subsidio --}}
                                            @if($permiso->subsidio_archivo)
                                                <div class="mt-2">
                                                    <a href="{{ asset('storage/' . $permiso->subsidio_archivo) }}" target="_blank"
                                                        class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 hover:underline">
                                                        <i class="fa fa-file mr-1 text-red-600"></i> Refrendamiento
                                                    </a>
                                                </div>
                                            @endif
                                        </td>


                                        <td class="px-6 py-4 text-sm">
                                            <button data-bs-toggle="modal" data-bs-target="#commentModal{{ $permiso->id }}"
                                                class="bg-yellow-500 text-white hover:bg-yellow-400 rounded-lg px-3 py-1 text-xs"
                                                title="Comentario">
                                                <i class="fas fa-comment-dots text-xl"></i>
                                            </button>

                                            @if($permiso->estado === 'pendiente' || $permiso->estado === 'pendiente_aprobacion')
                                                <!-- Botón para abrir el modal de aprobación o rechazo -->
                                                <button data-bs-toggle="modal" data-bs-target="#approveRejectModal{{ $permiso->id }}"
                                                    class="bg-blue-500 text-white hover:bg-blue-400 rounded-lg px-3 py-1 text-xs">
                                                    <i class="fas fa-check-circle text-xl"></i>
                                                </button>

                                            @endif

                                        </td>

                                    </tr>
                                    <!-- Modal de Aprobación/Rechazo -->
                                    <div class="modal fade" id="approveRejectModal{{ $permiso->id }}" tabindex="-1"
                                        aria-labelledby="approveRejectModalLabel{{ $permiso->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="approveRejectModalLabel{{ $permiso->id }}">Aprobar o
                                                        Rechazar
                                                        Solicitud de Permiso</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>¿Estás seguro de que deseas aprobar o rechazar esta solicitud de permiso?</p>

                                                    <form action="{{ route('admin.permisos.aprobar', $permiso->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="bg-green-500 text-white px-4 py-2 rounded-md">Aprobar</button>
                                                    </form>

                                                    <form action="{{ route('admin.permisos.declinar', $permiso->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="bg-red-500 text-white px-4 py-2 rounded-md">Rechazar</button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal para Comentario -->
                                    <div class="modal fade" id="commentModal{{ $permiso->id }}" tabindex="-1"
                                        aria-labelledby="commentModalLabel{{ $permiso->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="commentModalLabel{{ $permiso->id }}">Agregar un Comentario
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('admin.permisos.comentar', $permiso->id) }}" method="POST">
                                                        @csrf
                                                        <textarea name="comentario" rows="4" class="px-4 py-2 border rounded-md w-full"
                                                            required></textarea>
                                                        <button type="submit"
                                                            class="bg-indigo-600 text-white px-4 py-2 rounded-md mt-2">Guardar
                                                            Comentario</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                    @endforeach
                </tbody>
            </table>
            <!-- Paginación -->
            <div class="mt-6">
                {{ $permisos->appends(['estado' => request('estado'), 'empleado' => request('empleado')])->links('vendor.pagination.tailwind') }}
            </div>

            <!-- Tabla extra con permisos de empleados -->
            <h3 class="text-2xl font-semibold text-gray-800 mt-8">Permisos de Empleado</h3>
            <div class="overflow-x-auto shadow-md sm:rounded-lg mb-6">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-indigo-600 text-white text-center">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium">Empleado</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Tipo de Permiso</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Fechas</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Comentario</th>
                            <th class="px-6 py-3 text-left text-sm font-medium">Estado</th> <!-- Nueva columna -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permisosAdmin as $permiso)
                            <tr class="border-b border-gray-200">
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $permiso->empleado->nombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $permiso->tipoPermiso->nombre }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $permiso->fecha_inicio }} -
                                    {{ $permiso->fecha_fin }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $permiso->comentario }}</td>
                                <td class="px-6 py-4 text-sm font-semibold">
                                    @if ($permiso->estado === 'pendiente')
                                        <span class="px-2 py-1 rounded-full bg-yellow-500 text-white">
                                            Pendiente
                                        </span>
                                    @elseif ($permiso->estado === 'aprobado')
                                        <span class="px-2 py-1 rounded-full bg-green-500 text-white">
                                            Aprobado
                                        </span>
                                    @elseif ($permiso->estado === 'rechazado')
                                        <span class="px-2 py-1 rounded-full bg-red-500 text-white">
                                            Rechazado
                                        </span>
                                    @endif
                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection