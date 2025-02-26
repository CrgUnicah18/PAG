@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-7xl mx-auto">
        <h2 class="text-3xl font-semibold mb-6">Listado de Oficinas</h2>

        <div class="mb-4">
            <a href="{{ route('admin.configuracion.crear_oficina.create') }}" class="bg-blue-500 p-3 rounded-lg text-white">
                + Crear Nueva Oficina
            </a>
        </div>

        <table class="min-w-full table-auto">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Nombre</th>
                    <th class="px-4 py-2 border">Dirección</th>
                    <th class="px-4 py-2 border">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($oficinas as $oficina)
                    <tr>
                        <td class="px-4 py-2 border">{{ $oficina->id }}</td>
                        <td class="px-4 py-2 border">{{ $oficina->nombre }}</td>
                        <td class="px-4 py-2 border">{{ $oficina->direccion }}</td>
                        <td class="px-4 py-2 border">
                            <a href="#" class="text-blue-500">Editar</a> <!-- Puedes agregar la ruta de editar más adelante -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection