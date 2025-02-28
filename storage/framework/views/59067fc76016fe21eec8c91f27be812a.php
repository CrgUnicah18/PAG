<aside class="bg-purple-900 text-white w-64 h-screen fixed top-0 left-0 flex flex-col z-20">
    <div class="p-4 text-center border-b border-gray-700">
        <img src="<?php echo e(asset('images/logopag2.png')); ?>" alt="Logo de Aldea Global" class="mx-auto w-32 h-auto">
    </div>

    <nav class="flex-1 p-4">
        <ul class="space-y-2">
            <li>
                <a href="<?php echo e(route(auth()->user()->hasRole('admin') ? 'admin.inicio.home' : (auth()->user()->hasRole('supervisor') ? 'supervisor.inicio.home' : 'empleado.inicio.home'))); ?>"
                    class="block p-2 rounded-lg hover:bg-yellow-600 hover:text-white hover:border-none hover:shadow-xl hover:no-underline transition-all duration-300">
                    🏠 Inicio
                </a>
            </li>
            <?php if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor')): ?>
                <li>
                    <a href="<?php echo e(route(auth()->user()->hasRole('admin') ? 'admin.empleados.index' : 'supervisor.empleados.index')); ?>"
                        class="block p-2 rounded-lg hover:bg-yellow-600 hover:text-white hover:border-none hover:shadow-xl hover:no-underline transition-all duration-300">
                        👥 Empleados
                    </a>
                </li>
            <?php endif; ?>
            <?php if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor') || auth()->user()->hasRole('empleado')): ?>
                <li>
                    <a href="<?php echo e(route(auth()->user()->hasRole('admin') ? 'admin.permisos.index' : (auth()->user()->hasRole('supervisor') ? 'supervisor.permisos.index' : 'empleado.permisos.index'))); ?>"
                        class="block p-2 rounded-lg hover:bg-yellow-600 hover:text-white hover:border-none hover:shadow-xl hover:no-underline transition-all duration-300">
                        📜 Permisos
                    </a>
                </li>
            <?php endif; ?>
            <?php if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor') || auth()->user()->hasRole('empleado')): ?>
                <li>
                    <a href="<?php echo e(route(auth()->user()->hasRole('admin') ? 'admin.vacaciones.index' : (auth()->user()->hasRole('supervisor') ? 'supervisor.vacaciones.index' : 'empleado.vacaciones.index'))); ?>"
                        class="block p-2 rounded-lg hover:bg-yellow-600 hover:text-white hover:border-none hover:shadow-xl hover:no-underline transition-all duration-300">
                        🎉 Vacaciones
                    </a>
                </li>
            <?php endif; ?>
            <?php if(auth()->user()->hasRole('admin')): ?>
                <li>
                    <a href="<?php echo e(route('admin.configuracion.index')); ?>"
                        class="block p-2 rounded-lg hover:bg-yellow-600 hover:text-white hover:border-none hover:shadow-xl hover:no-underline transition-all duration-300">
                        ⚙️ Configuración
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</aside><?php /**PATH C:\laragon\www\PAG\resources\views/navbar.blade.php ENDPATH**/ ?>