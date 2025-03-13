@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <div class="bg-blue-600 text-white p-4 rounded-md shadow-md text-center text-lg font-bold">
            Crear Nuevo Tipo de Permiso
        </div>

        <!-- Mostrar mensajes de éxito -->
        @if(session('success'))
            <div class="alert alert-success flex items-center justify-between p-4 rounded mt-4">
                <span>{{ session('success') }}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
            </div>
        @endif

        <!-- Formulario para crear nuevo tipo de permiso -->
        <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
            <form method="POST" action="{{ route('admin.configuracion.tipos-permisos.store') }}">
                @csrf
                <div class="form-group mb-4">
                    <label for="nombre" class="font-semibold">Nombre del tipo de permiso</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required
                        placeholder="Ej. Permiso por enfermedad">
                </div>
                <div class="form-group mb-4">
                    <label for="descripcion" class="font-semibold">Descripción del tipo de permiso</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" required
                        placeholder="Ej. Permiso para ausencias por enfermedad o accidente"></textarea>
                </div>
                <div class="form-group mb-4">
                    <label for="dias" class="font-semibold">Duración (días)</label>
                    <input type="number" name="dias" id="dias" class="form-control" required placeholder="Ej. 5" min="1">
                </div>

                <div class="mb-4 flex items-center">
                    <input type="checkbox" id="es_vacacion" name="es_vacacion" value="1" class="mr-2" {{ old('es_vacacion', isset($tipoPermiso) ? $tipoPermiso->es_vacacion : 0) ? 'checked' : '' }}>
                    <label for="es_vacacion" class="font-semibold">Este permiso es de tipo "Vacaciones"</label>
                </div>

                <div class="form-group mb-4 flex items-center">
                    <input type="checkbox" id="es_licencia" name="es_licencia" value="1" class="mr-2" {{ old('es_licencia', isset($tipoPermiso) ? $tipoPermiso->es_licencia : 0) ? 'checked' : '' }}>
                    <label for="es_licencia" class="font-semibold">¿Es Licencia?</label>
                </div>

                <!-- Botones de guardar y cancelar -->
                <div class="form-group mt-6 flex justify-between">
                    <button type="submit" class="btn btn-primary w-1/2 flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i> Guardar
                    </button>
                    <a href="{{ route('admin.configuracion.tipos-permisos.index') }}"
                        class="btn btn-secondary w-1/2 flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection