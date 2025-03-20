<header class="bg-[rgb(124,37,105)] text-white shadow-lg p-2 pl-72 flex items-center fixed top-0 right-0 w-[84%] z-10">
    <!-- Botón para abrir el sidebar en pantallas pequeñas -->
    <button id="sidebarToggle" class="lg:hidden text-white mr-4">
        <i class="fas fa-bars"></i> <!-- Ícono de menú -->
    </button>

    <!-- Contenedor del menú del usuario -->
    <div class="ml-auto relative">
        <button id="userMenuButton" class="flex items-center gap-3 focus:outline-none">
            <!-- Imagen del usuario con sombra blanca -->
            <img src="<?php echo e(asset(Auth::user()->empleado->foto_perfil)); ?>" alt="Perfil"
                class="w-10 h-10 rounded-full object-cover shadow-lg shadow-[rgb(255,255,255)]">
            <h1 class="text-xl text-white"><?php echo e(Auth::user()->name); ?></h1>
        </button>

        <!-- Dropdown del menú de usuario -->
        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white text-black rounded-lg shadow-lg">
            <a href="#" class="block px-4 py-2 hover:bg-gray-200">Mi Perfil</a>
            <a href="<?php echo e(route('logout')); ?>" class="block px-4 py-2 hover:bg-gray-200">Cerrar sesión</a>
        </div>
    </div>
</header>

<script>
    // Abrir y cerrar el dropdown del usuario
    document.getElementById('userMenuButton').addEventListener('click', function () {
        document.getElementById('userDropdown').classList.toggle('hidden');
    });

    // Cierra el menú si haces clic fuera de él
    document.addEventListener('click', function (event) {
        let dropdown = document.getElementById('userDropdown');
        let button = document.getElementById('userMenuButton');

        if (!dropdown.contains(event.target) && !button.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
</script><?php /**PATH C:\laragon\www\PAG\resources\views/header.blade.php ENDPATH**/ ?>