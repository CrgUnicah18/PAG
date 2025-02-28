@extends('layouts.app')

@section('content')
    @if(auth()->user()->roles->isEmpty())
        <p>Este usuario no tiene rol asignado.</p>
    @endif

    @if(auth()->user()->hasRole('admin'))
        <div>
            <h3>Bienvenido, Administrador</h3>
            <!-- contenido exclusivo para admin -->
        </div>
    @endif

    @if(auth()->user()->hasRole('supervisor'))
        <div>
            <h3>Bienvenido, Supervisor</h3>
            <!-- contenido exclusivo para supervisor -->
        </div>
    @endif

    @if(auth()->user()->hasRole('empleado'))
        <div>
            <h3>Bienvenido, Empleado</h3>
            <!-- contenido exclusivo para empleado -->
        </div>
    @endif

    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

        <!-- Contadores de permisos -->
        <div class="bg-green-500 text-white p-6 rounded-lg">
            <h3 class="text-lg font-semibold">Permisos Pendientes</h3>
            <p class="mt-2 text-2xl">{{ $permisosPendientes }} permisos</p>
        </div>

        <div class="bg-yellow-500 text-white p-6 rounded-lg">
            <h3 class="text-lg font-semibold">Permisos Aprobados</h3>
            <p class="mt-2 text-2xl">{{ $permisosAprobados }} permisos</p>
        </div>

        <div class="bg-red-500 text-white p-6 rounded-lg">
            <h3 class="text-lg font-semibold">Permisos Rechazados</h3>
            <p class="mt-2 text-2xl">{{ $permisosRechazados }} permisos</p>
        </div>

        <div class="bg-blue-500 text-white p-6 rounded-lg">
            <h3 class="text-lg font-semibold">Permisos Pendientes de Aprobación</h3>
            <p class="mt-2 text-2xl">{{ $permisosPendienteAprobacion }} permisos</p>
        </div>

        <!-- Cumpleaños de hoy y mañana -->
        <div class="p-4 bg-blue-100 rounded-lg shadow-sm mb-6">
            <h3 class="text-2xl font-semibold text-gray-800 mb-2 flex items-center">
                <span class="mr-2 text-3xl">🎉</span>
                ¡Cumpleaños de Hoy!
            </h3>
            <ul class="list-disc pl-5">
                @foreach ($cumpleañosHoy as $empleado)
                    <li class="text-lg text-gray-700">{{ $empleado->nombre }}
                        ({{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m') }})</li>
                @endforeach
            </ul>

            <h3 class="text-2xl font-semibold text-gray-800 mt-6 mb-2 flex items-center">
                <span class="mr-2 text-3xl">🎂</span>
                ¡Cumpleaños de Mañana!
            </h3>
            <ul class="list-disc pl-5">
                @foreach ($cumpleañosMañana as $empleado)
                    <li class="text-lg text-gray-700">{{ $empleado->nombre }}
                        ({{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->format('d/m') }})</li>
                @endforeach
            </ul>
        </div>
    </div>

@endsection