<?php $__env->startSection('content'); ?>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Editar Duración del Permiso</h1>

        <!-- Mostrar mensajes de éxito -->
        <?php if(session('success')): ?>
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>


        <!-- Formulario para editar la duración -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <!-- Aquí usamos la ruta update de los recursos con el método PUT -->
            <form action="<?php echo e(route('configuracion.tipos-permisos.update', $tipoPermiso->id)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?> <!-- Aquí usas PATCH para la actualización -->

                <!-- Nombre del permiso -->
                <div class="mb-4">
                    <label for="nombre" class="block text-lg font-medium text-gray-700">Nombre del Tipo de Permiso:</label>
                    <input type="text" name="nombre" id="nombre" class="mt-2 block w-full p-3 border rounded"
                        value="<?php echo e(old('nombre', $tipoPermiso->nombre)); ?>" required>
                </div>

                <!-- Descripción del permiso -->
                <div class="mb-4">
                    <label for="descripcion" class="block text-lg font-medium text-gray-700">Descripción:</label>
                    <textarea name="descripcion" id="descripcion"
                        class="mt-2 block w-full p-3 border rounded"><?php echo e(old('descripcion', $tipoPermiso->descripcion)); ?></textarea>
                </div>

                <!-- Duración del permiso -->
                <div class="mb-4">
                    <label for="dias">Días</label>
                    <input type="number" name="dias" value="<?php echo e(old('dias', $tipoPermiso->dias)); ?>">

                </div>

                <div class="form-group mt-6 flex justify-between">
                    <button type="submit" class="btn btn-primary w-1/2 mr-2">Guardar
                        Cambios</button>
                    <a href="<?php echo e(route('configuracion.tipos-permisos.index')); ?>" class="btn btn-secondary w-1/2">Cancelar</a>
                </div>

            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\PAG\resources\views/configuracion/tipos-permisos/edit.blade.php ENDPATH**/ ?>