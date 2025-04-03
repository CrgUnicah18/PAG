@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 p-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">📢 Notificaciones de Permisos</h1>

        @if($permisos->count() > 0)
            <div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="p-3 text-left">Empleado</th>
                            <th class="p-3 text-left">Tipo de Permiso</th>
                            <th class="p-3 text-left">Fecha Inicio</th>
                            <th class="p-3 text-left">Fecha Fin</th>
                            <th class="p-3 text-left">Estado</th>
                            <th class="p-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permisos as $permiso)
                            <tr class="border-b hover:bg-gray-100 transition">
                                <td class="p-3">{{ $permiso->empleado->nombre }}</td>
                                <td class="p-3">{{ $permiso->tipoPermiso->nombre }}</td>
                                <td class="p-3">{{ $permiso->fecha_inicio }}</td>
                                <td class="p-3">{{ $permiso->fecha_fin }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 rounded-full text-white 
                                                    {{ $permiso->estado == 'pendiente' ? 'bg-yellow-500' : 'bg-blue-500' }}">
                                        {{ ucfirst($permiso->estado) }}
                                    </span>
                                </td>
                                <td class="p-3 text-center">
                                    <a href="{{ route('admin.permisos.index') }}"
                                        class="bg-blue-500 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-md transition">
                                        Ver Detalles
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $permisos->links() }}
            </div>
        @else
            <div class="bg-blue-100 border border-blue-500 text-blue-700 px-4 py-3 rounded relative">
                No hay notificaciones de permisos pendientes.
            </div>
        @endif
    </div>
@endsection