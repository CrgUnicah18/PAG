<header class="bg-[rgb(124,37,105)] text-white shadow-lg p-2 pl-72 flex items-center fixed top-0 right-0 w-[84%] z-10">
    <!-- Botón para abrir el sidebar en pantallas pequeñas -->
    <button id="sidebarToggle" class="lg:hidden text-white mr-4">
        <i class="fas fa-bars"></i> <!-- Ícono de menú -->
    </button>

    <!-- Contenedor de notificaciones y perfil con espacio -->
    <div class="ml-auto flex items-center gap-6">
        <!-- Botón de la campana -->
        <div class="relative flex items-center">
            <button id="notificacionesButton" class="relative focus:outline-none">
                <svg class="w-8 h-8 text-gray-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3c0 .386-.149.747-.405 1.02L4 17h5m6 0a3 3 0 11-6 0" />
                </svg>
                <!-- Contador de notificaciones -->
                @if($notificacionesCount > 0)
                    <span class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                        {{ $notificacionesCount }}
                    </span>
                @endif
            </button>

            <!-- Dropdown de notificaciones -->
            <div id="dropdownNotificaciones"
                class="hidden absolute right-0 top-full mt-3 w-80 bg-white shadow-md rounded-lg max-h-[300px] overflow-y-auto">
                <ul class="divide-y divide-gray-200">
                    @foreach($notificaciones as $notificacion)
                        <li class="px-4 py-2 hover:bg-gray-100">
                            <a href="{{ $notificacion['url'] }}" class="block text-gray-700">
                                {{ $notificacion['mensaje'] }}
                            </a>
                        </li>
                    @endforeach

                    @if($notificaciones->isEmpty())
                        <li class="p-3 text-gray-500 text-center">No hay nuevas notificaciones</li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Contenedor del menú del usuario -->
        <div class="relative">
            <button id="userMenuButton" class="flex items-center gap-3 focus:outline-none">
                <img src="{{ asset(Auth::user()->empleado->foto_perfil) }}" alt="Perfil"
                    class="w-10 h-10 rounded-full object-cover shadow-lg shadow-[rgb(255,255,255)]">
                <h1 class="text-xl text-white">{{ Auth::user()->name }}</h1>
            </button>

            <!-- Dropdown del menú de usuario -->
            <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white text-black rounded-lg shadow-lg">
                <a href="#" class="block px-4 py-2 hover:bg-gray-200">Mi Perfil</a>
                <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-gray-200">Cerrar sesión</a>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Toggle del dropdown de notificaciones
        document.getElementById('notificacionesButton').addEventListener('click', function () {
            document.getElementById("dropdownNotificaciones").classList.toggle("hidden");
        });

        // Cierra el dropdown de notificaciones si se hace clic fuera de él
        document.addEventListener('click', function (event) {
            let notifDropdown = document.getElementById('dropdownNotificaciones');
            let notifButton = document.getElementById('notificacionesButton');

            // Cerrar el dropdown si el clic está fuera del botón y el dropdown
            if (!notifDropdown.contains(event.target) && !notifButton.contains(event.target)) {
                notifDropdown.classList.add('hidden');
            }
        });
    });
</script>