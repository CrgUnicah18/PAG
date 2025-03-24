@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <div class="bg-blue-600 text-white p-4 rounded-md shadow-md text-center text-lg font-bold">
            Editar Tipo de Permiso
        </div>

        <!-- Mostrar mensajes de éxito -->
        @if(session('success'))
            <div class="alert alert-success flex items-center justify-between p-4 rounded mt-4">
                <span>{{ session('success') }}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
            </div>
        @endif

        <!-- Formulario para editar tipo de permiso -->
        <div class="bg-white shadow-lg rounded-lg p-6 mt-6">
            <form method="POST" action="{{ route('admin.configuracion.tipos-permisos.update', $tipoPermiso->id) }}">
                @csrf
                @method('PATCH')
                <div class="form-group mb-4">
                    <label for="nombre" class="font-semibold">Nombre del tipo de permiso</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required
                        value="{{ old('nombre', $tipoPermiso->nombre) }}">
                </div>
                <div class="form-group mb-4">
                    <label for="descripcion" class="font-semibold">Descripción del tipo de permiso</label>
                    <textarea name="descripcion" id="descripcion" class="form-control"
                        required>{{ old('descripcion', $tipoPermiso->descripcion) }}</textarea>
                </div>
                <div class="form-group mb-4">
                    <label for="dias" class="font-semibold">Duración (días)</label>
                    <input type="number" name="dias" id="dias" class="form-control" required
                        value="{{ old('dias', $tipoPermiso->dias) }}" min="1">
                </div>

                <div class="mb-4 flex items-center">
                    <input type="hidden" name="es_vacacion" value="0">
                    <label class="switch">
                        <input type="checkbox" id="es_vacacion" name="es_vacacion" value="1" {{ old('es_vacacion', $tipoPermiso->es_vacacion ?? false) ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <label for="es_vacacion" class="font-semibold">Este permiso es de tipo "Vacaciones"</label>
                </div>

                <div class="form-group mb-4 flex items-center">
                    <input type="hidden" name="es_licencia" value="0">
                    <label class="switch">
                        <input type="checkbox" id="es_licencia" name="es_licencia" value="1" {{ old('es_licencia', isset($tipoPermiso) ? $tipoPermiso->es_licencia : 0) ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <label for="es_licencia" class="font-semibold">¿Es Licencia Femenina?</label>
                </div>

                <div class="form-group mb-4 flex items-center">
                    <input type="hidden" name="es_licenciam" value="0">
                    <label class="switch">
                        <input type="checkbox" id="es_licenciam" name="es_licenciam" value="1" {{ old('es_licenciam', isset($tipoPermiso) ? $tipoPermiso->es_licenciam : 0) ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <label for="es_licenciam" class="font-semibold">¿Es Licencia Masculina?</label>
                </div>


                <div class="mb-4 flex items-center">
                    <input type="hidden" name="requiere_subsidio" value="0">
                    <label class="switch">
                        <input type="checkbox" id="requiere_subsidio" name="requiere_subsidio" value="1" {{ old('requiere_subsidio', isset($tipoPermiso) ? $tipoPermiso->requiere_subsidio : 0) ? 'checked' : '' }}>
                        <span class="slider round"></span>
                    </label>
                    <label for="requiere_subsidio" class="font-semibold">Refrendamiento</label>
                </div>




                <!-- Botones de guardar y cancelar -->
                <div class="form-group mt-6 flex justify-between">
                    <button type="submit" class="btn btn-primary w-1/2 flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i> Guardar Cambios
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