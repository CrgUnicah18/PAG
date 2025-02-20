@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-xl font-semibold text-gray-800 shadow-sm bg-gray-100 p-3 rounded-md">Eliminar Empleado</h1>

        <!-- Mostrar mensajes de éxito -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filtro de búsqueda -->
        <form method="GET" action="{{ route('configuracion.eliminar-empleado.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <input type="text" name="nombre" class="form-control" placeholder="Buscar por nombre"
                        value="{{ request()->get('nombre') }}">
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-success">Buscar</button>
                </div>
            </div>
        </form>

        <!-- Tabla de empleados -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($empleados as $empleado)
                    <tr>
                        <td>{{ $empleado->nombre }}</td>
                        <td>
                            <!-- Botón para eliminar empleado -->
                            <form method="POST" action="{{ route('configuracion.eliminar-empleado.destroy', $empleado->id) }}"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este empleado?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection