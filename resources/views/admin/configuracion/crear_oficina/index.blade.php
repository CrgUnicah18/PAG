@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-7xl mx-auto">
        <div class="bg-white shadow-xl rounded-xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-800">Listado de Oficinas</h2>
                <a href="{{ route('admin.configuracion.crear_oficina.create') }}"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg shadow transition">
                    + Crear Nueva Oficina
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden shadow">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="text-left px-6 py-3 border-b">ID</th>
                            <th class="text-left px-6 py-3 border-b">Nombre</th>
                            <th class="text-left px-6 py-3 border-b">Dirección</th>
                            <th class="text-left px-6 py-3 border-b">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($oficinas as $oficina)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 border-b">{{ $oficina->id }}</td>
                                <td class="px-6 py-4 border-b font-medium">{{ $oficina->nombre }}</td>
                                <td class="px-6 py-4 border-b">{{ $oficina->direccion }}</td>
                                <td class="px-6 py-4 border-b">
                                    <a href="{{ route('admin.configuracion.edit_oficina.edit', $oficina->id) }}"
                                        class="inline-flex items-center text-blue-600 hover:text-blue-800 font-semibold transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3zM3 21h18" />
                                        </svg>
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        @if ($oficinas->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500">No hay oficinas registradas.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection