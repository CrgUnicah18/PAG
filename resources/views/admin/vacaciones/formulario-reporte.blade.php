@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-2xl font-semibold text-center mb-6">Generar Reporte de Vacaciones</h2>

        <form action="{{ route('admin.vacaciones.generar-reporte') }}" method="GET" class="space-y-6">
            @csrf <!-- Aunque no es necesario para GET, lo dejo por si tienes otros formularios que usen POST -->

            <!-- Campo de empleado -->
            <div class="form-group">
                <label for="empleado_id" class="block text-gray-700 font-medium">Empleado</label>
                <select name="empleado_id" id="empleado_id"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    @foreach ($empleados as $empleado)
                        <option value="{{ $empleado->id }}"
                            {{ old('empleado_id', request('empleado_id')) == $empleado->id ? 'selected' : '' }}>
                            {{ $empleado->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Campo de fecha de inicio -->
            <div class="form-group">
                <label for="fecha_inicio" class="block text-gray-700 font-medium">Fecha de inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('fecha_inicio', request('fecha_inicio')) }}">
            </div>

            <!-- Campo de fecha de fin -->
            <div class="form-group">
                <label for="fecha_fin" class="block text-gray-700 font-medium">Fecha de fin</label>
                <input type="date" name="fecha_fin" id="fecha_fin"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('fecha_fin', request('fecha_fin')) }}">
            </div>

            <!-- Filtro de estado con checkboxes -->
            <div class="form-group">
                <label class="block text-gray-700 font-medium">Estado</label>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <input type="checkbox" name="estado[]" value="pendiente" id="estado_pendiente" class="mr-2"
                            {{ in_array('pendientes', old('estado', [])) ? 'checked' : '' }}>
                        <label for="estado_pendiente" class="text-gray-600">Pendiente</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="estado[]" value="pendientes_aprobacion"
                            id="estado_pendiente_aprobacion" class="mr-2"
                            {{ in_array('pendientes_aprobacion', old('estado', [])) ? 'checked' : '' }}>
                        <label for="estado_pendiente_aprobacion" class="text-gray-600">Pendiente Aprobación</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="estado[]" value="aprobadas" id="estado_aprobada" class="mr-2"
                            {{ in_array('aprobadas', old('estado', [])) ? 'checked' : '' }}>
                        <label for="estado_aprobada" class="text-gray-600">Aprobada</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="estado[]" value="rechazadas" id="estado_rechazada" class="mr-2"
                            {{ in_array('rechazadas', old('estado', [])) ? 'checked' : '' }}>
                        <label for="estado_rechazada" class="text-gray-600">Rechazada</label>
                    </div>
                </div>
            </div>

            <!-- Campos seleccionables con checkboxes -->
<div class="form-group">
    <label class="block text-gray-700 font-medium">Seleccionar campos a mostrar</label>
    <div class="space-y-3">
        <div class="flex items-center">
            <input type="checkbox" name="campos[]" value="empleado_id" id="empleado_id_checkbox" class="mr-2"
                {{ in_array('empleado_id', old('campos', [])) ? 'checked' : '' }}>
            <label for="empleado_id_checkbox" class="text-gray-600">Empleado</label>
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="campos[]" value="fecha_inicio" id="fecha_inicio_checkbox" class="mr-2"
                {{ in_array('fecha_inicio', old('campos', [])) ? 'checked' : '' }}>
            <label for="fecha_inicio_checkbox" class="text-gray-600">Fecha de inicio</label>
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="campos[]" value="fecha_fin" id="fecha_fin_checkbox" class="mr-2"
                {{ in_array('fecha_fin', old('campos', [])) ? 'checked' : '' }}>
            <label for="fecha_fin_checkbox" class="text-gray-600">Fecha de fin</label>
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="campos[]" value="duracion_dias" id="duracion_dias_checkbox" class="mr-2"
                {{ in_array('duracion_dias', old('campos', [])) ? 'checked' : '' }}>
            <label for="duracion_dias_checkbox" class="text-gray-600">Duración de días</label>
        </div>

        <!-- Estado checkbox siempre seleccionado y oculto -->
        <div class="flex items-center" style="display:none;">
            <input type="checkbox" name="campos[]" value="estado" id="estado_checkbox" class="mr-2" checked>
            <label for="estado_checkbox" class="text-gray-600">Estado</label>
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="campos[]" value="tipo_permiso_id" id="tipo_permiso_id_checkbox" class="mr-2"
                {{ in_array('tipo_permiso_id', old('campos', [])) ? 'checked' : '' }}>
            <label for="tipo_permiso_id_checkbox" class="text-gray-600">Tipo de permiso</label>
        </div>
        <div class="flex items-center">
            <input type="checkbox" name="campos[]" value="comentario" id="comentario_checkbox" class="mr-2"
                {{ in_array('comentario', old('campos', [])) ? 'checked' : '' }}>
            <label for="comentario_checkbox" class="text-gray-600">Comentario</label>
        </div>
    </div>
</div>


            <!-- Botón para generar el reporte -->
            <div class="form-group">
                <button type="submit"
                    class="w-full py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Generar reporte
                </button>
            </div>
        </form>
    </div>
@endsection
