@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Empleados';
    @endphp
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-xl font-semibold text-gray-800">📋 Listado de Empleados</h2>
            <a href="{{ route('admin.empleados.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus-circle"></i> Crear Empleado
            </a>
        </div>

        {{-- FORMULARIO DE BÚSQUEDA --}}
        <form method="GET" action="{{ route('admin.empleados.index') }}">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="nombre" class="text-sm">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" class="form-control form-control-sm"
                        placeholder="Buscar por nombre" value="{{ request()->get('nombre') }}">
                </div>
                <div class="col-md-3">
                    <label for="grupo_id" class="text-sm">Grupo:</label>
                    <select name="grupo_id" id="grupo_id" class="form-control form-control-sm">
                        <option value="">Selecciona un grupo</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id }}" {{ request()->get('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                {{ $grupo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="oficina_id" class="text-sm">Oficina:</label>
                    <select name="oficina_id" id="oficina_id" class="form-control form-control-sm">
                        <option value="">Selecciona una oficina</option>
                        @foreach($oficinas as $oficina)
                            <option value="{{ $oficina->id }}" {{ request()->get('oficina_id') == $oficina->id ? 'selected' : '' }}>
                                {{ $oficina->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="estado" class="text-sm">Estado:</label>
                    <select name="estado" id="estado" class="form-control form-control-sm">
                        <option value="">Todos</option>
                        <option value="activo" {{ request()->get('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="inactivo" {{ request()->get('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        <option value="terminado" {{ request()->get('estado') == 'terminado' ? 'selected' : '' }}>Terminado</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end mt-3 ml-auto">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>
        </form>

        {{-- FORMULARIO DE SELECCIÓN DE CAMPOS PARA REPORTE --}}
        <div class="mb-3">
            <h4>Generar Reporte</h4>
            <form method="GET" action="{{ route('admin.empleados.report') }}">
                <div class="form-group">
                    <label for="campos" class="text-sm">Seleccionar campos para reporte:</label><br>
                    <input type="checkbox" name="campos[]" value="nombre" checked> Nombre
                    <input type="checkbox" name="campos[]" value="apellido" checked> Apellido
                    <input type="checkbox" name="campos[]" value="telefono" checked> Teléfono
                    <input type="checkbox" name="campos[]" value="grupo" checked> Grupo
                    <input type="checkbox" name="campos[]" value="oficina" checked> Oficina
                    <input type="checkbox" name="campos[]" value="supervisor" checked> Supervisor
                    <input type="checkbox" name="campos[]" value="estado" checked> Estado
                </div>
                <div class="form-group">
                    <label for="formato" class="text-sm">Seleccionar formato:</label>
                    <select name="formato" id="formato" class="form-control form-control-sm">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-info btn-sm">
                    <i class="fas fa-download"></i> Descargar Reporte
                </button>
            </form>
        </div>

        {{-- TABLA DE EMPLEADOS --}}
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover shadow-sm">
                <thead class="bg-indigo-600 text-white text-center">
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Teléfono</th>
                        <th>Grupo</th>
                        <th>Oficina</th>
                        <th>Supervisor</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empleados as $empleado)
                        <tr class="text-center">
                            <td>{{ $empleado->nombre }}</td>
                            <td>{{ $empleado->apellido }}</td>
                            <td>{{ $empleado->telefono }}</td>
                            <td>{{ $empleado->grupo->nombre }}</td>
                            <td>{{ $empleado->oficina->nombre }}</td>
                            <td>{{ $empleado->supervisor ? $empleado->supervisor->nombre : 'N/A' }}</td>
                            <td>
                                <span class="badge {{ $empleado->estado == 'activo' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($empleado->estado) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.empleados.show', $empleado->id) }}" class="btn btn-info"
                                        title="Ver perfil">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.empleados.edit', $empleado->id) }}" class="btn btn-warning"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
