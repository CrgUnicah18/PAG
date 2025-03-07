<form action="{{ route('admin.permisos.update', $permiso->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label for="comentario" class="block text-lg">Comentario</label>
        <textarea name="comentario" id="comentario" rows="4" class="form-textarea mt-1 block w-full"
            placeholder="Agregar comentario">{{ old('comentario', $permiso->comentario) }}</textarea>
    </div>

    <button type="submit" class="bg-blue-500 text-white p-2 rounded-lg">Actualizar comentario</button>
</form>