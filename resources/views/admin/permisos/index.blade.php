@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6">Lista de Permisos</h2>

        <!-- Filtro de estado (Arriba de la tabla de todos los permisos) -->
        <form method="GET" action="{{ route('admin.permisos.index') }}" class="mb-6">
            <div class="flex items-center justify-between">
                <label for="estado" class="text-lg font-medium text-gray-700">Filtrar por estado:</label>
                <select name="estado" id="estado"
                    class="form-select mt-1 block w-1/3 py-2 px-3 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    onchange="this.form.submit()">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="pendiente_aprobacion" {{ request('estado') == 'pendiente_aprobacion' ? 'selected' : '' }}>
                        Pendiente de Aprobación</option>
                    <option value="aprobado" {{ request('estado') == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                    <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                </select>
            </div>
        </form>
        {{-- Botón para crear un nuevo permiso --}}
        <a href="{{ route('admin.permisos.create') }}" class="btn btn-primary">Solicitar Permiso</a>

        <!-- Tabla de permisos (todos los permisos de todos los empleados) -->
        <div class="overflow-x-auto shadow-md sm:rounded-lg mb-6">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-indigo-600 text-white text-center">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium ">Empleado</th>
                        <th class="px-6 py-3 text-left text-sm font-medium ">Tipo de Permiso</th>
                        <th class="px-6 py-3 text-left text-sm font-medium ">Fechas</th>
                        <th class="px-6 py-3 text-left text-sm font-medium ">Estado</th>
                        <th class="px-6 py-3 text-left text-sm font-medium ">Comentario</th>
                        <th class="px-6 py-3 text-left text-sm font-medium ">Acciones</th>
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
                                @if($permiso->estado == 'pendiente')
                                    <span class="text-orange-600">Pendiente</span>
                                @elseif($permiso->estado == 'pendiente_aprobacion')
                                    <span class="text-blue-600">Pendiente de Aprobación</span>
                                @elseif($permiso->estado == 'aprobado')
                                    <span class="text-green-600">Aprobado</span>
                                @else
                                    <span class="text-red-600">Rechazado</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($permiso->comentario)
                                    {{ $permiso->comentario }}
                                @else
                                    <span class="text-gray-400">No hay comentario</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <button onclick="toggleModal('{{ $permiso->id }}')"
                                    class="bg-yellow-500 text-white hover:bg-yellow-400 rounded-lg px-3 py-1 text-xs"
                                    title="Comentario">
                                    <i class="fas fa-comment-dots text-xl"></i>
                                </button>

                                @if($permiso->estado === 'pendiente' || $permiso->estado === 'pendiente_aprobacion')
                                    <button class="bg-blue-500 text-white hover:bg-blue-400 rounded-lg px-3 py-1 text-xs"
                                        data-toggle="modal" data-target="#approveRejectModal{{ $permiso->id }}">
                                        <i class="fas fa-check-circle text-xl"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal de Aprobación/Rechazo -->
                        <div class="modal fade" id="approveRejectModal{{ $permiso->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="approveRejectModalLabel{{ $permiso->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-gray-100">
                                        <h5 class="modal-title" id="approveRejectModalLabel{{ $permiso->id }}">Aprobar o
                                            Rechazar Permiso</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Estás seguro de que deseas aprobar o rechazar esta solicitud de permiso?
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('admin.permisos.aprobar', $permiso->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="bg-green-600 text-white px-4 py-2 rounded-md">Aprobar</button>
                                        </form>

                                        <form action="{{ route('admin.permisos.declinar', $permiso->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="bg-red-600 text-white px-4 py-2 rounded-md">Rechazar</button>
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
                {{ $permisos->appends(['estado' => request('estado')])->links('vendor.pagination.tailwind') }}
            </div>
        </div>

        <!-- Tabla de "Mis Solicitudes de Permiso" (Al final de la página) -->
        <h4 class="mb-4">Mis Solicitudes de Permiso</h4>
        <table class="table table-striped table-hover table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Empleado</th>
                    <th>Tipo de Permiso</th>
                    <th>Fecha de Inicio</th>
                    <th>Fecha de Fin</th>
                    <th>Estado</th>
                    <th>Comentario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permisosAdmin as $permiso)
                    <tr>
                        <td>{{ $permiso->empleado->nombre }} {{ $permiso->empleado->apellido }}</td>
                        <td>{{ $permiso->tipoPermiso->nombre }}</td>
                        <td>{{ \Carbon\Carbon::parse($permiso->fecha_inicio)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($permiso->fecha_fin)->format('d/m/Y') }}</td>
                        <td>
                            @if($permiso->estado == 'pendiente')
                                <span class="badge badge-warning">Pendiente</span>
                            @elseif($permiso->estado == 'pendiente_aprobacion')
                                <span class="badge badge-primary">Pendiente de Aprobación</span>
                            @elseif($permiso->estado == 'aprobado')
                                <span class="badge badge-success">Aprobado</span>
                            @elseif($permiso->estado == 'rechazado')
                                <span class="badge badge-danger">Rechazado</span>
                            @endif
                        </td>
                        <td>
                            @if($permiso->comentario)
                                {{ $permiso->comentario }}
                            @else
                                <span class="text-gray-400">No hay comentario</span>
                            @endif
                        </td>
                        <td>
                            @if($permiso->estado == 'pendiente' || $permiso->estado == 'pendiente_aprobacion')
                                <span class="badge badge-info">Su solicitud esta siendo procesada</span>
                            @else
                                <span class="badge badge-info">Solicitud procesada</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal de Comentario (Flotante) -->
    @foreach ($permisos as $permiso)
        <div id="commentModal{{ $permiso->id }}"
            class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <h2 class="text-2xl font-semibold mb-4">Comentario para el Permiso de {{ $permiso->empleado->nombre }}</h2>

                <!-- Formulario de comentario -->
                <form action="{{ route('admin.permisos.comentar', $permiso->id) }}" method="POST">
                    @csrf
                    <textarea class="w-full p-2 border rounded" name="comentario" rows="4"
                        placeholder="Escribe tu comentario...">{{ old('comentario') }}</textarea>

                    <!-- Botón de guardar -->
                    <button type="submit" class="mt-4 bg-indigo-600 text-white py-2 px-4 rounded-lg">Guardar</button>
                </form>
            </div>
        </div>
    @endforeach

@endsection

<script>
    document.querySelectorAll('.modal').forEach(modal => {
        modal.querySelector('form').addEventListener('submit', function (event) {
            event.preventDefault();
            const modalId = modal.id;

            // Enviar el formulario con AJAX o refrescar la página para guardar el comentario
            fetch(modal.querySelector('form').action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    comentario: modal.querySelector('textarea').value
                })
            }).then(response => {
                if (response.ok) {
                    // Cerrar el modal
                    document.getElementById(modalId).classList.add('hidden');
                    location.reload(); // Recargar la página si se necesita ver el comentario actualizado
                } else {
                    alert('Hubo un error al guardar el comentario.');
                }
            });
        });
    });
</script>