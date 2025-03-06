@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center">Solicitudes de Vacaciones</h1>

        <table class="table table-hover table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Empleado</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Estado</th>
                    <th>Comentario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vacacionesGenerales as $vacacion)
                        <tr>
                            <td>{{ $vacacion->empleado->nombre }} {{ $vacacion->empleado->apellido }}</td>
                            <td>{{ $vacacion->fecha_inicio }}</td>
                            <td>{{ $vacacion->fecha_fin }}</td>
                            <td>
                                <span class="badge bg-{{ $vacacion->estado == 'pendiente' ? 'warning' :
                    ($vacacion->estado == 'aprobadas' ? 'success' :
                        ($vacacion->estado == 'pendientes_aprobacion' ? 'primary' : 'danger')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $vacacion->estado)) }}
                                </span>

                            </td>
                            <td>{{ $vacacion->comentario ?? 'Sin comentario' }}</td>
                            <td>
                                @if($vacacion->estado == 'pendiente' || $vacacion->estado == 'pendientes_aprobacion')
                                    <button class="btn btn-success me-2" onclick="confirmAction('aprobar', {{ $vacacion->id }})">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-danger me-2" onclick="confirmAction('rechazar', {{ $vacacion->id }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                                <button class="btn btn-info" onclick="openCommentModal({{ $vacacion->id }})">
                                    <i class="fas fa-comment"></i>
                                </button>
                            </td>
                        </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal de Confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="closeConfirmModal()"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmMessage"></p>
                </div>
                <div class="modal-footer">
                    <form id="confirmForm" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        onclick="closeConfirmModal()">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Comentarios -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Comentario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="closeCommentModal()"></button>
                </div>
                <div class="modal-body">
                    <form id="commentForm" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="comentario" class="form-label">Comentario</label>
                            <textarea class="form-control" id="comentario" name="comentario" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            onclick="closeCommentModal()">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmAction(action, id) {
            let message = action === 'aprobar' ? '¿Estás seguro de que quieres aprobar esta solicitud?' : '¿Estás seguro de que quieres rechazar esta solicitud?';
            let route = action === 'aprobar' ? "{{ url('admin/vacaciones/aprobar') }}/" + id : "{{ url('admin/vacaciones/declinar') }}/" + id;

            document.getElementById('confirmMessage').textContent = message;
            document.getElementById('confirmForm').action = route;

            let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            confirmModal.show();
        }

        function closeConfirmModal() {
            let confirmModalInstance = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
            if (confirmModalInstance) confirmModalInstance.hide();
        }

        function closeCommentModal() {
            let commentModalInstance = bootstrap.Modal.getInstance(document.getElementById('commentModal'));
            if (commentModalInstance) commentModalInstance.hide();
        }

        function openCommentModal(id) {
            let route = "{{ url('admin/vacaciones/addComentario') }}/" + id;
            document.getElementById('commentForm').action = route;

            let commentModal = new bootstrap.Modal(document.getElementById('commentModal'));
            commentModal.show();
        }
    </script>

@endsection