@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Título -->
        <h2 class="text-2xl font-semibold mb-6">Bienvenido, {{ $empleado->nombre }}</h2>

        <!-- Foto de perfil y datos básicos -->
        <div class="flex items-center space-x-4 mb-6">
            <img src="{{ $empleado->foto_perfil }}" alt="Foto de perfil"
                class="w-16 h-16 rounded-full border-2 border-gray-300">
            <div>
                <p class="text-lg font-semibold">{{ $empleado->nombre }}</p>
                <p class="text-sm text-gray-500">Estado: {{ $empleado->estado }}</p>
            </div>
        </div>

        <!-- Sección de Permisos -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
            <!-- Permisos Pendientes -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-2">Permisos Pendientes</h3>
                <p>{{ $empleado->permisosPendientesCount }} Permisos pendientes.</p>
                <a href="{{ route('user.permisos.index') }}" class="text-blue-600">Ver más</a>
            </div>

            <!-- Permisos Aprobados -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-2">Permisos Aprobados</h3>
                <p>{{ $empleado->permisosAprobadosCount }} Permisos aprobados.</p>
                <a href="{{ route('user.permisos.index') }}" class="text-blue-600">Ver más</a>
            </div>
        </div>

        <!-- Sección de Vacaciones -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
            <!-- Vacaciones Próximas -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-2">Vacaciones Próximas</h3>
                <p>{{ $empleado->vacacionesProximas }}</p>
                <a href="{{ route('user.vacaciones.index') }}" class="text-blue-600">Ver más</a>
            </div>

            <!-- Días de Vacaciones Disponibles -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-2">Días de Vacaciones Disponibles</h3>
                <p>{{ $empleado->vacacionesDisponibles }} días restantes.</p>
            </div>
        </div>

        <!-- Notificaciones de Cumpleaños -->
        <div class="bg-white p-4 rounded-lg shadow-md mb-6">
            <h3 class="text-xl font-semibold mb-2">Cumpleaños Próximos</h3>
            <ul>
                @foreach($empleado->cumpleañosProximos as $cumpleaños)
                    <li>{{ $cumpleaños->nombre }} - {{ $cumpleaños->fecha }}</li>
                @endforeach
            </ul>
        </div>
    </div>

@endsection