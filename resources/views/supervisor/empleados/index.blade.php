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
        @php
@endphp 



        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-xl font-semibold text-gray-800">📋 Listado de Empleados</h2>
        </div>
       

        {{-- FORMULARIO DE BÚSQUEDA --}}
        <form method="GET" action="{{ route('supervisor.empleados.index') }}">
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

        {{-- TABLA DE EMPLEADOS --}}
        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover shadow-sm">
                <thead class="bg-indigo-600 text-white text-center">
                    <tr>
                        <th>Nombre Completo</th>
                        <th>Teléfono</th>
                        <th>Grupo</th>
                        <th>Oficina</th>
                        <th>Supervisor</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empleados as $empleado)
                        <tr class="text-center">
                            <td>{{ $empleado->nombre }} {{ $empleado->apellido }}</td>
                            <td>{{ $empleado->telefono }}</td>
                            <td>{{ $empleado->grupo->nombre }}</td>
                            <td>{{ $empleado->oficina->nombre }}</td>
                            <td>{{ $empleado->supervisor ? $empleado->supervisor->nombre : 'N/A' }}</td>
                            <td>{{ $empleado->user ? $empleado->user->email : 'No asignado' }}</td>

                            <td>
                                @foreach(optional($empleado->user)->roles ?? collect() as $rol)
                                    {{ $rol->name }} @if (!$loop->last), @endif
                                @endforeach
                            </td>

                            <td>
                                <span class="badge {{ $empleado->estado == 'activo' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($empleado->estado) }}
                                </span>
                            </td>

                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('supervisor.empleados.show', $empleado->id) }}" class="btn btn-info"
                                       title="Ver perfil">
                                        <i class="fas fa-eye"></i>
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
