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

        <div class="d-flex justify-content-between mb-4">
            <h2>Listado de Empleados</h2>
            <a href="{{ route('empleados.create') }}" class="btn btn-primary">Crear Empleado</a>
        </div>

        <form method="GET" action="{{ route('empleados.index') }}">
            <div class="row mb-4">
                <div class="col-md-4">
                    <input type="text" name="nombre" class="form-control" placeholder="Buscar por nombre"
                        value="{{ request()->get('nombre') }}">
                </div>
                <div class="col-md-4">
                    <select name="grupo_id" class="form-control">
                        <option value="">Seleccionar grupo</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id }}" {{ request()->get('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                {{ $grupo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="oficina_id" class="form-control">
                        <option value="">Seleccionar oficina</option>
                        @foreach($oficinas as $oficina)
                            <option value="{{ $oficina->id }}" {{ request()->get('oficina_id') == $oficina->id ? 'selected' : '' }}>{{ $oficina->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Buscar</button>
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
                            <a href="{{ route('empleados.edit', ['empleado' => $empleado->id]) }}"
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