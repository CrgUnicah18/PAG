@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold text-gray-800">Listado de Programas</h2>

        <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden shadow mt-3">
            <thead class="text-white" style="background-color: rgb(124, 37, 105);">
                <tr class="border-b">
                    <th class="px-6 py-3 text-left text-sm font-semibold">ID</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Oficina</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grupos as $grupo)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $grupo->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $grupo->nombre }}</td>
                        @foreach ($oficinas as $oficina)
                            @if ($grupo->oficina_id == $oficina->id)
                                <td class="px-6 py-4 text-sm text-gray-800">{{ $oficina->nombre }}</td>
                            @endif

                        @endforeach
                        <td class="px-6 py-4 text-sm text-gray-800">
                            <a href="{{ route('admin.configuracion.edit_grupo.edit', $grupo->id) }}"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow-sm transition duration-300">
                                Editar
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection