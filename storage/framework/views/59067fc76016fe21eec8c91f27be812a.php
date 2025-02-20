<!-- Navbar (actualizado) -->
<aside class="bg-gray-800 text-white w-64 h-screen fixed top-0 left-0 flex flex-col z-20">
    <div class="p-4 text-center text-lg font-bold border-b border-gray-700">
        Proyecto Aldea Global
    </div>
    <nav class="flex-1 p-4">
        <ul class="space-y-2">
            <li><a href="#" class="block p-2 rounded hover:bg-gray-700">🏠 Inicio</a></li>
            <li><a href="<?php echo e(route('empleados.index')); ?>" class="block p-2 rounded hover:bg-gray-700">👥
                    Empleados</a></li>
            <li><a href="#" class="block p-2 rounded hover:bg-gray-700">📜 Permisos</a></li>
            <li><a href="#" class="block p-2 rounded hover:bg-gray-700">🎉 Vacaciones</a></li>
            <li><a href="<?php echo e(route('configuracion.index')); ?>" class="block p-2 rounded hover:bg-gray-700">⚙️
                    Configuración</a></li>
        </ul>
    </nav>
</aside><?php /**PATH C:\laragon\www\PAG\resources\views/navbar.blade.php ENDPATH**/ ?>