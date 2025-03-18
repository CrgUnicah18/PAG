@extends('layouts.app')

@section('content')
    <div class="relative">
        <!-- Icono de regresar en la esquina superior izquierda -->
        <a href="{{ route('admin.vacaciones.reporte') }}"
            class="absolute top-4 left-4 inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-full shadow-lg">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver al Formulario
        </a>

        <div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
            <h2 class="text-2xl font-semibold text-center mb-4">Reporte de Vacaciones</h2>
             <!-- Botones para exportar a PDF y Excel -->
             <div class="mb-6 flex justify-between">
                <a href="{{ route('admin.vacaciones.exportarPDF', request()->all()) }}"
                    class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-full shadow-lg">
                    Exportar a PDF
                </a>

                <a href="{{ route('admin.vacaciones.exportarExcel', request()->all()) }}"
                    class="inline-flex items-center bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-full shadow-lg">
                    Exportar a Excel
                </a>
            </div>

            <table class="min-w-full bg-white border border-gray-300 shadow-md">
                <thead>
                    <tr>
                        <!-- Aquí estamos reemplazando los nombres de los campos por los nombres más amigables -->
                        @foreach ($camposSeleccionados as $campo)
                            <th class="px-6 py-3 text-left text-gray-600">
                                @switch($campo)
                                    @case('empleado_id')
                                        Empleado
                                        @break
                                    @case('tipo_permiso_id')
                                        Tipo de Permiso
                                        @break
                                    @case('fecha_inicio')
                                        Fecha de Inicio
                                        @break
                                    @case('fecha_fin')
                                        Fecha de Fin
                                        @break
                                    @case('duracion_dias')
                                        Duración (días)
                                        @break
                                    @case('estado')
                                        Estado
                                        @break
                                    @case('comentario')
                                        Comentario
                                        @break
                                    @default
                                        {{ ucfirst(str_replace('_', ' ', $campo)) }}
                                @endswitch
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vacaciones as $vacacion)
                        <tr>
                            @foreach ($camposSeleccionados as $campo)
                                <td class="px-6 py-4 border-t text-gray-600">
                                    @if($campo == 'empleado_id')
                                        {{ $vacacion->empleado->nombre }}
                                    @elseif($campo == 'tipo_permiso_id')
                                        {{ $vacacion->tipoPermiso->nombre }}
                                    @elseif($campo == 'estado')
                                        @php
                                            $estadoClase = '';
                                            $estadoTexto = ucfirst($vacacion->estado);

                                            // Asignar colores según el estado
                                            switch ($vacacion->estado) {
                                                case 'pendientes':
                                                    $estadoClase = 'bg-yellow-100 text-yellow-800'; // Amarillo para pendientes
                                                    break;
                                                case 'pendientes_aprobacion':
                                                    $estadoClase = 'bg-blue-100 text-blue-800'; // Azul para pendientes de aprobación
                                                    break;
                                                case 'aprobadas':
                                                    $estadoClase = 'bg-green-100 text-green-800'; // Verde para aprobadas
                                                    break;
                                                case 'rechazadas':
                                                    $estadoClase = 'bg-red-100 text-red-800'; // Rojo para rechazadas
                                                    break;
                                                default:
                                                    $estadoClase = 'bg-gray-100 text-gray-800'; // Gris por defecto
                                            }
                                        @endphp
                                        <span class="px-4 py-2 rounded-full {{ $estadoClase }}">
                                            {{ $estadoTexto }}
                                        </span>
                                    @else
                                        {{ $vacacion->$campo }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="mt-6">
            {{ $vacaciones->appends(request()->query())->links() }}
            </div>

        </div>
    </div>
@endsection
