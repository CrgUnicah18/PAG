@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-semibold mb-4">Asignar Vacaciones</h2>

        <form method="POST" action="{{ route('admin.vacaciones.store') }}">
            @csrf
            <div class="mb-4">
                <label for="empleado_id" class="block text-lg">Empleado:</label>
                <select name="empleado_id" id="empleado_id" class="form-select mt-1 block w-full">
                    @foreach ($empleados as $empleado)
                        <option value="{{ $empleado->id }}">{{ $empleado->nombre }} {{ $empleado->apellido }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="fecha_inicio" class="block text-lg">Fecha de Inicio:</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-input mt-1 block w-full" required>
            </div>

            <div class="mb-4">
                <label for="fecha_fin" class="block text-lg">Fecha de Fin:</label>
                <input type="date" name="fecha_fin" id="fecha_fin" class="form-input mt-1 block w-full" required>
            </div>

            <div class="mb-4">
                <label for="tipo_permiso_id" class="block text-lg">Tipo de Permiso:</label>
                <select name="tipo_permiso_id" id="tipo_permiso_id" class="form-select mt-1 block w-full">
                    @foreach ($tiposPermiso as $tipoPermiso)
                        <option value="{{ $tipoPermiso->id }}" {{ $tipoPermiso->is_vacaciones ? 'selected' : '' }}>
                            {{ $tipoPermiso->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success">Asignar Vacaciones</button>
        </form>
    </div>
@endsection