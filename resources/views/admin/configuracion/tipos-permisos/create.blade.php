@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-xl font-semibold text-gray-800 shadow-sm bg-gray-100 p-3 rounded-md">Crear nuevo tipo de permiso
        </h1>

        <!-- Mostrar mensajes de éxito -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Formulario para crear nuevo tipo de permiso -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <form method="POST" action="{{ route('admin.configuracion.tipos-permisos.store') }}">
                @csrf
                <div class="form-group mb-4">
                    <label for="nombre">Nombre del tipo de permiso</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required
                        placeholder="Ej. Permiso por enfermedad">
                </div>
                <div class="form-group mb-4">
                    <label for="descripcion">Descripción del tipo de permiso</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" required
                        placeholder="Ej. Permiso para ausencias por enfermedad o accidente"></textarea>
                </div>
                <div class="form-group mb-4">
                    <label for="dias">Duración (días)</label>
                    <input type="number" name="dias" id="dias" class="form-control" required placeholder="Ej. 5" min="1">
                </div>

                <div class="mb-4">
                    <label for="es_vacacion" class="flex items-center">
                        <input type="checkbox" id="es_vacacion" name="es_vacacion" value="1" class="mr-2">
                        Este permiso es de tipo "Vacaciones"
                    </label>
                </div>


                <!-- Botones de guardar y cancelar -->
                <div class="form-group mt-6 flex justify-between">
                    <button type="submit" class="btn btn-primary w-1/2 mr-2">Guardar</button>
                    <a href="{{ route('admin.configuracion.tipos-permisos.index') }}"
                        class="btn btn-secondary w-1/2">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@endsection