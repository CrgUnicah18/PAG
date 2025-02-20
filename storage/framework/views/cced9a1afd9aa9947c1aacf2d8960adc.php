

<?php $__env->startSection('content'); ?>
    <?php
        $pageTitle = 'Empleados';
    ?>
    <div class="container mt-4">
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php elseif(session('error')): ?>
            <div class="alert alert-danger">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between mb-4">
            <h2>Listado de Empleados</h2>
            <a href="<?php echo e(route('empleados.create')); ?>" class="btn btn-primary">Crear Empleado</a>
        </div>

        <form method="GET" action="<?php echo e(route('empleados.index')); ?>">
            <div class="row mb-4">
                <div class="col-md-4">
                    <input type="text" name="nombre" class="form-control" placeholder="Buscar por nombre"
                        value="<?php echo e(request()->get('nombre')); ?>">
                </div>
                <div class="col-md-4">
                    <select name="grupo_id" class="form-control">
                        <option value="">Seleccionar grupo</option>
                        <?php $__currentLoopData = $grupos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grupo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($grupo->id); ?>" <?php echo e(request()->get('grupo_id') == $grupo->id ? 'selected' : ''); ?>>
                                <?php echo e($grupo->nombre); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="oficina_id" class="form-control">
                        <option value="">Seleccionar oficina</option>
                        <?php $__currentLoopData = $oficinas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $oficina): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($oficina->id); ?>" <?php echo e(request()->get('oficina_id') == $oficina->id ? 'selected' : ''); ?>><?php echo e($oficina->nombre); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Buscar</button>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Grupo</th>
                    <th>Oficina</th>
                    <th>Supervisor</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $empleados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $empleado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($empleado->nombre); ?></td>
                        <td><?php echo e($empleado->apellido); ?></td>
                        <td><?php echo e($empleado->telefono); ?></td>
                        <td><?php echo e($empleado->grupo->nombre); ?></td>
                        <td><?php echo e($empleado->oficina->nombre); ?></td>
                        <td><?php echo e($empleado->supervisor ? $empleado->supervisor->nombre : 'N/A'); ?></td>
                        <td>
                            <span class="badge <?php echo e($empleado->estado == 'activo' ? 'bg-success' : 'bg-danger'); ?>">
                                <?php echo e(ucfirst($empleado->estado)); ?>

                            </span>
                        </td>
                        <td class="text-center">
                            <!-- Icono para editar -->
                            <a href="<?php echo e(route('empleados.edit', ['empleado' => $empleado->id])); ?>"
                                class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> <!-- Icono de lápiz para editar -->
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\PAG\resources\views/empleados/index.blade.php ENDPATH**/ ?>