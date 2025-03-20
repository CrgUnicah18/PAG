@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        {{-- Título centrado y con estilo --}}
<div class="text-center mb-6">
    <h2 class="text-4xl font-bold text-gray-800 border-b-4 border-blue-600 inline-block pb-2">
        📝 Solicitud de Permisos
    </h2>
</div>

{{-- Botones con íconos --}}
<div class="flex justify-center gap-4 mb-6">
    {{-- Botón para solicitar permiso --}}
    <a href="{{ route('supervisor.permisos.create') }}"
        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-md transition"
        style="background-color: rgb(231, 173, 33); color: white; padding: 10px 20px; border: none; border-radius: 8px;">
        <i data-lucide="plus-circle" class="w-5 h-5"></i>
        Solicitar Permiso
    </a>

    {{-- Botón para ver lista de permisos --}}
    <a href="{{ route('supervisor.permisos.lista') }}"
        class="inline-flex items-center gap-2 bg-gray-700 hover:bg-gray-800 text-white font-medium py-2 px-4 rounded-lg shadow-md transition">
        <i data-lucide="list" class="w-5 h-5"></i>
        Ver Lista de tipos de permisos
    </a>
</div>




        @if(session('success'))
            <div class="alert alert-success mb-4 rounded-lg">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger mb-4 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- // TODO: Tabla de los empleados bajo su cargo -->

        <!-- Estilo para los permisos de los empleados a cargo (con acciones de aprobar o rechazar) -->
        <h4 class="text-xl font-semibold text-gray-800 mt-8 mb-4 border-b-2 border-gray-300 pb-2">
            Permisos de mis empleados
        </h4>

        @if($permisosEmpleados->isEmpty())
            <p class="text-center text-gray-500">No tienes empleados bajo tu supervisión o no hay permisos solicitados.</p>
        @else
            <div class="table-responsive">
                <table class="min-w-full table-auto border-collapse bg-white shadow-lg rounded-lg">
                    <thead class="text-white rounded-t-lg" style="background-color: rgb(117, 178, 59);">
                        <tr>
                            <th class="py-2 px-4 border-b text-left rounded-tl-lg">Empleado</th>
                            <th class="py-2 px-4 border-b text-left">Tipo de Permiso</th>
                            <th class="py-2 px-4 border-b text-left">Fecha de Inicio</th>
                            <th class="py-2 px-4 border-b text-left">Fecha de Fin</th>
                            <th class="py-2 px-4 border-b text-left">Comentario</th>
                            <th class="py-2 px-4 border-b text-left">Estado</th>
                            <th class="py-2 px-4 border-b text-left rounded-tr-lg">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permisosEmpleados as $permiso)
                            <tr class="hover:bg-gray-100">
                                <td class="py-2 px-4 border-b">{{ $permiso->empleado->nombre }} {{ $permiso->empleado->apellido }}</td>
                                <td class="py-2 px-4 border-b">{{ $permiso->tipoPermiso->nombre }}</td>
                                <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($permiso->fecha_inicio)->format('d/m/Y') }}</td>
                                <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($permiso->fecha_fin)->format('d/m/Y') }}</td>
                                <td class="py-2 px-4 border-b">
                                    @if($permiso->estado == 'pendiente')
                                        <span class="badge bg-warning text-white">Pendiente</span>
                                    @elseif($permiso->estado == 'pendiente_aprobacion')
                                        <span class="badge bg-primary text-white">Pendiente de Aprobación</span>
                                    @elseif($permiso->estado == 'aprobado')
                                        <span class="badge bg-success text-white">Aprobado</span>
                                    @elseif($permiso->estado == 'rechazado')
                                        <span class="badge bg-danger text-white">Rechazado</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b">
                                    @if($permiso->comentario)
                                        {{ $permiso->comentario }}
                                    @else
                                        <span class="text-gray-400">No hay comentario</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b">
                                    @if($permiso->estado == 'pendiente')
                                        <!-- Botones de acción para aprobar o rechazar permisos -->
                                        <form action="{{ route('supervisor.permisos.aprobar', $permiso->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success rounded-lg">Pre-Aprobar</button>
                                        </form>
                                        <form action="{{ route('supervisor.permisos.declinar', $permiso->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger rounded-lg">Rechazar</button>
                                        </form>
                                    @elseif($permiso->estado == 'pendiente_aprobacion')
                                        <span class="badge bg-info text-white">Esperando aprobación del Admin</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Paginación de los permisos de los empleados -->
                <div class="mt-4">
                    {{ $permisosEmpleados->links() }}
                </div>
            </div>
        @endif

        <!-- Tabla de permisos del supervisor -->
        <div class="table-responsive">
            <h4 class="text-xl font-semibold text-gray-800 mt-8 mb-4 border-b-2 border-gray-300 pb-2">
                Permisos del Supervisor
            </h4>
            <table class="min-w-full table-auto border-collapse bg-white shadow-lg rounded-lg">
                <thead class="text-white rounded-t-lg" style="background-color: rgb(117, 178, 59);">
                    <tr>
                        <th class="py-2 px-4 border-b text-left rounded-tl-lg">Empleado</th>
                        <th class="py-2 px-4 border-b text-left">Tipo de Permiso</th>
                        <th class="py-2 px-4 border-b text-left">Fecha de Inicio</th>
                        <th class="py-2 px-4 border-b text-left">Fecha de Fin</th>
                        <th class="py-2 px-4 border-b text-left">Estado</th>
                        <th class="py-2 px-4 border-b text-left">Comentario</th>
                        <th class="py-2 px-4 border-b text-left rounded-tr-lg">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permisosSupervisor as $permiso)
                        <tr class="hover:bg-gray-100">
                            <td class="py-2 px-4 border-b">{{ $permiso->empleado->nombre }} {{ $permiso->empleado->apellido }}</td>
                            <td class="py-2 px-4 border-b">{{ $permiso->tipoPermiso->nombre }}</td>
                            <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($permiso->fecha_inicio)->format('d/m/Y') }}</td>
                            <td class="py-2 px-4 border-b">{{ \Carbon\Carbon::parse($permiso->fecha_fin)->format('d/m/Y') }}</td>
                            <td class="py-2 px-4 border-b">
                                @if($permiso->estado == 'pendiente')
                                    <span class="badge bg-warning text-white">Pendiente</span>
                                @elseif($permiso->estado == 'pendiente_aprobacion')
                                    <span class="badge bg-primary text-white">Pendiente de Aprobación</span>
                                @elseif($permiso->estado == 'aprobado')
                                    <span class="badge bg-success text-white">Aprobado</span>
                                @elseif($permiso->estado == 'rechazado')
                                    <span class="badge bg-danger text-white">Rechazado</span>
                                @endif
                            </td>
                            <td class="py-2 px-4 border-b">
                                @if($permiso->comentario)
                                    {{ $permiso->comentario }}
                                @else
                                    <span class="text-gray-400">No hay comentario</span>
                                @endif
                            </td>
                            <td class="py-2 px-4 border-b">
                                <span class="badge bg-info text-white">Solo admin puede aprobar</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginación de los permisos del supervisor -->
            <div class="mt-4">
                {{ $permisosSupervisor->links() }}
            </div>
        </div>

    </div>
    <script>
        lucide.createIcons();
    </script>
@endsection
