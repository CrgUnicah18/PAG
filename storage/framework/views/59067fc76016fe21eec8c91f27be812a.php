<?php
    $bgColor = auth()->user()->hasRole('admin') ? 'bg-red-900' :
        (auth()->user()->hasRole('supervisor') ? 'bg-blue-900' : 'bg-green-900');
?>

<aside class="<?php echo e($bgColor); ?> text-white w-64 h-screen fixed top-0 left-0 flex flex-col z-20">
    <!-- Sección de la imagen (bloque superior) -->
    <div class="p-4 text-center border-b-4 border-yellow-600">
        <img src="<?php echo e(asset('images/logopag2.png')); ?>" alt="Logo de Aldea Global" class="mx-auto w-32 h-auto">
    </div>

    <!-- Sección de navegación (bloque inferior) -->
    <nav class="flex-1 p-4">
        <ul class="space-y-2">
            <li>
                <a href="<?php echo e(route(auth()->user()->hasRole('admin') ? 'admin.inicio.home' : (auth()->user()->hasRole('supervisor') ? 'supervisor.inicio.home' : 'empleado.inicio.home'))); ?>"
                    class="block p-2 rounded-lg hover:bg-yellow-600 hover:text-white hover:border-none hover:shadow-xl hover:no-underline transition-all duration-300">
                    🏠 Inicio
                </a>
            </li>
            <?php if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('supervisor') || auth()->user()->hasRole('empleado')): ?>
                <li>
                    <a href="<?php echo e(route(auth()->user()->hasRole('admin') ? 'admin.anuncios.index' : (auth()->user()->hasRole('supervisor') ? 'supervisor.anuncios.index' : 'empleado.anuncios.index'))); ?>"
                        class="block p-2 rounded-lg hover:bg-yellow-600 hover:text-white hover:border-none hover:shadow-xl hover:no-underline transition-all duration-300">
                        📢 Anuncios
                    </a>
                </li>
            <?php endif; ?>

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

    <!-- Sección de Cerrar sesión (al final del sidebar) -->
    <div class="p-4 mt-auto border-t border-gray-700">
        <form action="<?php echo e(route('logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit"
                class="block w-full py-2 px-4 bg-red-600 text-white rounded-lg hover:bg-red-800 transition-all duration-300">
                <i class="fas fa-sign-out-alt mr-2"></i> Cerrar sesión
            </button>
        </form>
    </div>
</aside><?php /**PATH C:\laragon\www\PAG\resources\views/navbar.blade.php ENDPATH**/ ?>