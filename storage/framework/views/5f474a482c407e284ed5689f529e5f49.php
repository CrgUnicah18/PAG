

<?php $__env->startSection('content'); ?>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Crear Nuevo Tipo de Permiso</h1>

        <!-- Mostrar mensajes de éxito -->
        <?php if(session('success')): ?>
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <!-- Formulario para crear nuevo tipo de permiso -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <form method="POST" action="<?php echo e(route('configuracion.tipos-permisos.store')); ?>">
                <?php echo csrf_field(); ?>
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

                <!-- Botón de guardar desplazado un poco hacia abajo -->
                <div class="form-group mt-6">
                    <button type="submit" class="btn btn-primary w-full">Guardar</button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\PAG\resources\views/configuracion/tipos-permisos/create.blade.php ENDPATH**/ ?>