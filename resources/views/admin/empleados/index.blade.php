@extends('layouts.app')

@section('content')
    @php
        $pageTitle = 'Empleados';
    @endphp
    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800 shadow-sm bg-gray-100 p-3 rounded-md">
                Listado de Empleados
            </h2>
            <a href="{{ route('admin.empleados.create') }}" class="btn btn-primary">
                Crear Empleado
            </a>
        </div>


        <form method="GET" action="{{ route('admin.empleados.index') }}">
            <div class="row mb-4 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="nombre" class="form-control" placeholder="Buscar por nombre"
                        value="{{ request()->get('nombre') }}">
                </div>
                <div class="col-md-3">
                    <select name="grupo_id" class="form-control">
                        <option value="">Seleccionar grupo</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id }}" {{ request()->get('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                {{ $grupo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="oficina_id" class="form-control">
                        <option value="">Seleccionar oficina</option>
                        @foreach($oficinas as $oficina)
                            <option value="{{ $oficina->id }}" @if(request()->get('oficina_id') == $oficina->id) selected @endif>
                                {{ $oficina->nombre }}
                            </option>

                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex">
                    <button type="submit" class="btn btn-success d-flex align-items-center">
                        <i class="fas fa-search mr-2"></i> Buscar
                    </button>
                </div>
            </div>
        </form>

        <table class="table">
            <thead>
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
                    <tr>
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
                        <td class="text-center">
                            <!-- Icono para editar -->
                            <a href="{{ route('admin.empleados.edit', ['empleado' => $empleado->id]) }}"
                                class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> <!-- Icono de lápiz para editar -->
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection