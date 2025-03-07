@extends('layouts.app')

@section('content')
    <div class="p-6 max-w-7xl mx-auto">
        <h2 class="text-3xl font-semibold mb-6">Listado de Grupos</h2>

        <table class="min-w-full table-auto">
            <thead>
                <tr>
                    <th class="px-4 py-2 border">ID</th>
                    <th class="px-4 py-2 border">Nombre</th>
                    <th class="px-4 py-2 border">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grupos as $grupo)
                    <tr>
                        <td class="px-4 py-2 border">{{ $grupo->id }}</td>
                        <td class="px-4 py-2 border">{{ $grupo->nombre }}</td>
                        <td class="px-4 py-2 border">
                            <!-- Aquí puedes agregar botones o enlaces para editar o eliminar el grupo -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection