@extends('layouts.app')

@section('content')

    <a href="{{ route('empleado.permisos.index') }}" class="btn btn-secondary mb-3 m-3">
        ← Volver
    </a>

    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h2 class="mb-0">📝 Tipos de Permisos</h2>
            </div>
            <div class="card-body">
                <!-- Mensaje de éxito -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Tabla de tipos de permisos -->
                <div class="table-responsive">
                    <table class="table table-bordered text-center table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Días</th>
                                <th>¿Es Vacación?</th>
                                <th>¿Es Licencia Femenina?</th>
                                <th>¿Es Licencia Masculina?</th>
                                <th>¿Es Subsidio?</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($tiposPermiso) && $tiposPermiso->count() > 0)
                                @foreach ($tiposPermiso as $tipoPermiso)
                                    <tr class="hover-row">
                                        <td>{{ $tipoPermiso->nombre }}</td>
                                        <td>{{ $tipoPermiso->descripcion }}</td>
                                        <td>{{ $tipoPermiso->dias }}</td>
                                        <td>{!! $tipoPermiso->es_vacacion ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>' !!}</td>
                                        <td>{!! $tipoPermiso->es_licencia ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>' !!}</td>
                                        <td>{!! $tipoPermiso->es_licenciam ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>' !!}</td>
                                        <td>{!! $tipoPermiso->es_subsidio ? '<i class="fas fa-check-circle text-success"></i>' : '<i class="fas fa-times-circle text-danger"></i>' !!}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No hay tipos de permisos registrados.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        /* Estilo de la tabla */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            font-size: 1rem;
        }

        table th {
            background-color: #f8f9fa;
            color: #495057;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #e9ecef;
            cursor: pointer;
        }

        /* Añadir iconos a las respuestas */
        i {
            font-size: 1.2rem;
        }

        .btn {
            font-size: 0.875rem;
        }
    </style>
@endpush
