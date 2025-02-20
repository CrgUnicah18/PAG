<?php $__env->startSection('content'); ?>
    <div class="container mt-4">
        <h2>Editar Empleado</h2>

        <!-- Verificación y visualización de errores -->
        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Formulario para editar empleado -->
        <form method="POST" action="<?php echo e(route('empleados.update', $empleado->id)); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?> <!-- Indicamos que es una actualización -->

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" value="<?php echo e(old('nombre', $empleado->nombre)); ?>"
                        required>
                </div>
                <div class="col-md-4">
                    <label for="apellido" class="form-label">Apellido</label>
                    <input type="text" class="form-control" name="apellido"
                        value="<?php echo e(old('apellido', $empleado->apellido)); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" name="telefono"
                        value="<?php echo e(old('telefono', $empleado->telefono)); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="grupo_id" class="form-label">Grupo</label>
                    <select class="form-control" name="grupo_id" required>
                        <?php $__currentLoopData = $grupos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grupo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($grupo->id); ?>" <?php echo e((old('grupo_id', $empleado->grupo_id) == $grupo->id) ? 'selected' : ''); ?>>
                                <?php echo e($grupo->nombre); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="oficina_id" class="form-label">Oficina</label>
                    <select class="form-control" name="oficina_id" required>
                        <?php $__currentLoopData = $oficinas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oficina): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($oficina->id); ?>" <?php echo e(old('oficina_id', $empleado->oficina_id) == $oficina->id ? 'selected' : ''); ?>>
                                <?php echo e($oficina->nombre); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Supervisor -->
                <div class="col-md-4">
                    <label for="supervisor_id" class="form-label">Supervisor</label>
                    <select class="form-control" name="supervisor_id">
                        <option value="">Seleccionar Supervisor</option>
                        <?php $__currentLoopData = $supervisores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supervisor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($supervisor->id); ?>" <?php echo e(old('supervisor_id', $empleado->supervisor_id) == $supervisor->id ? 'selected' : ''); ?>>
                                <?php echo e($supervisor->nombre); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <!-- Estado -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-control" name="estado" required>
                            <option value="activo" <?php echo e(old('estado', $empleado->estado) == 'activo' ? 'selected' : ''); ?>>Activo
                            </option>
                            <option value="inactivo" <?php echo e(old('estado', $empleado->estado) == 'inactivo' ? 'selected' : ''); ?>>
                                Inactivo</option>
                        </select>
                    </div>
                </div>

            </div>

            <button type="submit" class="btn btn-success">Actualizar Empleado</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\PAG\resources\views/empleados/edit.blade.php ENDPATH**/ ?>