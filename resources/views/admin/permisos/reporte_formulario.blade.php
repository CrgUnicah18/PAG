@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">Generar Reporte de Permisos</h1>

        <form action="{{ route('admin.permisos.reporte') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
            @csrf
            <div class="mb-4">
                <label for="empleado_id" class="block text-sm font-medium text-gray-700">Empleado:</label>
                <select name="empleado_id" id="empleado_id"
                    class="form-control mt-2 block w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="todos">Todos</option>
                    @foreach ($empleados as $empleado)
                        <option value="{{ $empleado->id }}">
                            {{ $empleado->nombre . ' ' . $empleado->apellido }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="tipo_permiso_id" class="block text-sm font-medium text-gray-700">Tipo de Permiso:</label>
                <select name="tipo_permiso_id" id="tipo_permiso_id">
                    <option value="todos">Todos</option> <!-- Antes estaba "" -->
                    @foreach ($tiposPermiso as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>

            </div>

            <div class="mb-4">
                <label for="mes" class="block text-sm font-medium text-gray-700">Mes:</label>
                <select name="mes" id="mes">
                    <option value="todos">Todos</option> <!-- Antes estaba "" -->
                    <option value="1">Enero</option>
                    <option value="2">Febrero</option>
                    <option value="3">Marzo</option>
                    <option value="4">Abril</option>
                    <option value="5">Mayo</option>
                    <option value="6">Junio</option>
                    <option value="7">Julio</option>
                    <option value="8">Agosto</option>
                    <option value="9">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="anio" class="block text-sm font-medium text-gray-700">Año:</label>
                <input type="number" name="anio" id="anio"
                    class="form-control mt-2 block w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ date('Y') }}" required>
            </div>

            <div class="mb-4">
                <label for="estado" class="block text-sm font-medium text-gray-700">Estado:</label>
                <select name="estado" id="estado">
                    <option value="">Todos</option> <!-- Ahora es "" en lugar de "todos" -->
                    <option value="aprobado">Aprobado</option>
                    <option value="rechazado">Rechazado</option>
                    <option value="pendiente">Pendiente</option>
                </select>
            </div>


            <button type="submit"
                class="w-full bg-blue-500 text-white p-3 rounded-md hover:bg-blue-600 transition duration-300">Solicitar
                Reporte</button>
        </form>
    </div>
@endsection