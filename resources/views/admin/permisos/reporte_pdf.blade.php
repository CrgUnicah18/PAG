@extends('layouts.app')

@section('content')
    <!-- Contenedor principal -->
    <div class="container mx-auto p-6 bg-white rounded-lg shadow-lg">
        <!-- Botón Regresar -->
        <div class="mb-4">
            <a href="{{ route('admin.permisos.formulario') }}" class="text-blue-500 hover:text-blue-700 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                    Regresar al Formulario
            </a>
        </div>

        <!-- Título del reporte -->
        <div class="text-center mb-6">
            <h1 class="text-3xl font-semibold text-gray-800">Reporte de Permisos</h1>
            <h3 class="text-xl text-gray-600 mt-2">Mes: 
                @if($mes && $mes != 'todos')
                    {{ \Carbon\Carbon::createFromFormat('m', $mes)->format('F') }}
                @else
                    Todos los Meses
                @endif 
                de {{ $anio }}
            </h3>
        </div>

        <!-- Mostrar permisos si existen -->
        @if ($permisos->count())
            @foreach ($permisos->groupBy(function($item) {
                return \Carbon\Carbon::parse($item->fecha_inicio)->format('F Y'); // Agrupar por mes y año
            }) as $mesAnio => $permisosPorMes)
                <!-- Encabezado de mes -->
                <div class="my-6">
                    <h2 class="text-2xl font-semibold text-gray-700">{{ $mesAnio }}</h2>

                    <!-- Tabla de permisos -->
                    <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md mt-4">
                        <thead class="bg-blue-500 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left">Empleado</th>
                                <th class="px-6 py-3 text-left">Fecha de Inicio</th>
                                <th class="px-6 py-3 text-left">Fecha de Fin</th>
                                <th class="px-6 py-3 text-left">Tipo de Permiso</th>
                                <th class="px-6 py-3 text-left">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($permisosPorMes as $permiso) <!-- Paginación de 5 por mes -->
                                <tr class="border-b hover:bg-gray-100">
                                    <td class="px-6 py-3">{{ $permiso->empleado->nombre }} {{ $permiso->empleado->apellido }}</td>
                                    <td class="px-6 py-3">{{ $permiso->fecha_inicio }}</td>
                                    <td class="px-6 py-3">{{ $permiso->fecha_fin }}</td>
                                    <td class="px-6 py-3">{{ $permiso->tipo_permiso }}</td>
                                    <td class="px-6 py-3">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                            {{ $permiso->estado == 'aprobado' ? 'bg-green-500 text-white' : ($permiso->estado == 'pendiente' ? 'bg-yellow-500 text-white' : 'bg-red-500 text-white') }}">
                                            {{ $permiso->estado }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Paginación -->
                    <div class="mt-4">
                        <!-- Paginación general para los permisos agrupados -->
                        {{ $permisos->links() }} <!-- Enlace de paginación general -->
                    </div>
                </div>
            @endforeach
        @else
            <!-- Mensaje si no hay permisos -->
            <div class="text-center py-4">
                <p class="text-lg text-gray-500">No se encontraron permisos para este filtro.</p>
            </div>
        @endif

        <!-- Botón para descargar el PDF -->
        <div class="text-center mt-6">
            <form action="{{ route('admin.permisos.reporte.pdf') }}" method="GET">
                @csrf
                <input type="hidden" name="empleado_id" value="{{ $empleadoId }}">
                <input type="hidden" name="tipo_permiso_id" value="{{ $tipoPermisoId }}">
                <input type="hidden" name="mes" value="{{ $mes }}">
                <input type="hidden" name="anio" value="{{ $anio }}">
                <button type="submit" class="btn btn-primary bg-blue-500 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-md">
                    Descargar PDF
                </button>
            </form>
        </div>
    </div>
@endsection
