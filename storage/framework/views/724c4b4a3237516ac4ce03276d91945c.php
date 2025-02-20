<?php $__env->startSection('content'); ?>
    <div class="container">
        <h1 class="text-xl font-semibold text-gray-800 shadow-sm bg-gray-100 p-3 rounded-md">
            Tipos de permisos
        </h1>




        <!-- Mensaje de éxito si existe -->
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <div class="flex justify-end mb-2 p-3 relative right-7">
            <a href="<?php echo e(route('configuracion.tipos-permisos.create')); ?>" class="btn btn-primary">
                Crear permiso
            </a>
        </div>

        <!-- Tabla de tipos de permisos -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Días</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($tiposPermiso) && $tiposPermiso->count() > 0): ?>
                    <?php $__currentLoopData = $tiposPermiso; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipoPermiso): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($tipoPermiso->nombre); ?></td>
                            <td><?php echo e($tipoPermiso->descripcion); ?></td>
                            <td><?php echo e($tipoPermiso->dias); ?></td>
                            <td>
                                <a href="<?php echo e(route('configuracion.tipos-permisos.edit', $tipoPermiso->id)); ?>"
                                    class="btn btn-warning">Editar</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No hay tipos de permisos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\PAG\resources\views/configuracion/tipos-permisos/index.blade.php ENDPATH**/ ?>