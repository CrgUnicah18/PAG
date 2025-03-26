@extends('layouts.app')

@section('content')
    <div class="container mx-auto mb-4 p-6">
        <h1 class="text-2xl font-bold text-gray-800 bg-gray-100 p-4 rounded-md shadow-md">
            Lista de empleados
        </h1>

        <!-- Mostrar mensajes de éxito -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-md my-4 shadow-md">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filtro de búsqueda -->
        <form method="GET" action="{{ route('admin.configuracion.eliminar-empleado.index') }}" class="mb-6 mt-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <!-- Campo de búsqueda -->
                <div>
                    <label for="nombre" class="block text-sm font-semibold text-gray-700">Buscar por nombre:</label>
                    <input type="text" name="nombre" id="nombre"
                        class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300"
                        placeholder="Escribe un nombre" value="{{ request()->get('nombre') }}">
                </div>

                <!-- Selección de programa -->
                <div>
                    <label for="grupo_id" class="block text-sm font-semibold text-gray-700">Programa:</label>
                    <select name="grupo_id" id="grupo_id"
                        class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300">
                        <option value="">Selecciona un programa</option>
                        @foreach($grupos as $grupo)
                            <option value="{{ $grupo->id }}" {{ request()->get('grupo_id') == $grupo->id ? 'selected' : '' }}>
                                {{ $grupo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Selección de oficina -->
                <div>
                    <label for="oficina_id" class="block text-sm font-semibold text-gray-700">Oficina:</label>
                    <select name="oficina_id" id="oficina_id"
                        class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300">
                        <option value="">Selecciona una oficina</option>
                        @foreach($oficinas as $oficina)
                            <option value="{{ $oficina->id }}" {{ request()->get('oficina_id') == $oficina->id ? 'selected' : '' }}>
                                {{ $oficina->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Selección de estado -->
                <div>
                    <label for="estado" class="block text-sm font-semibold text-gray-700">Estado:</label>
                    <select name="estado" id="estado"
                        class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-300">
                        <option value="">Seleccionar estado</option>
                        <option value="activo" {{ request()->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="terminado" {{ request()->estado == 'terminado' ? 'selected' : '' }}>Terminado</option>
                        <option value="inactivo" {{ request()->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <!-- Botón de búsqueda -->
                <div class="md:col-span-4 flex justify-end">
                    <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded-md shadow-md hover:bg-blue-600 transition-all">
                        Buscar
                    </button>
                </div>
            </div>

        </form>

        <!-- Tabla de empleados -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse bg-white shadow-md rounded-lg">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 text-left">Nombre Completo</th>
                        <th class="p-3 text-left">Oficina</th>
                        <th class="p-3 text-left">Programa</th>
                        <th class="p-3 text-left">Estado</th>
                        <th class="p-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($empleados as $empleado)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="p-3">{{ $empleado->nombre }} {{ $empleado->apellido }}</td>
                            <td class="p-3">{{ $empleado->oficina->nombre }}</td>
                            <td class="p-3">{{ $empleado->grupo->nombre }}</td>
                            <td class="p-3">
                                <span
                                    class="px-3 py-1 rounded-full text-white text-sm 
                                            {{ $empleado->estado == 'activo' ? 'bg-green-500' : ($empleado->estado == 'terminado' ? 'bg-red-500' : 'bg-yellow-500') }}">
                                    {{ ucfirst($empleado->estado) }}
                                </span>
                            </td>
                            <td class="p-3 text-center space-x-2">
                                @if($empleado->estado != 'terminado')
                                    <form method="POST"
                                        action="{{ route('admin.configuracion.eliminar-empleado.destroy', $empleado->id) }}"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-red-500 text-white px-3 py-2 rounded-md shadow-md hover:bg-red-600"
                                            onclick="return confirm('¿Estás seguro de que deseas cambiar el estado de este empleado a terminado?')">
                                            Terminar
                                        </button>
                                    </form>
                                @endif

                                @if($empleado->estado == 'terminado')
                                    <form method="POST"
                                        action="{{ route('admin.configuracion.recontratar-empleado', $empleado->id) }}"
                                        class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="bg-blue-500 text-white px-3 py-2 rounded-md shadow-md hover:bg-blue-600"
                                            onclick="return confirm('¿Deseas recontratar a este empleado?')">
                                            Recontratar
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection