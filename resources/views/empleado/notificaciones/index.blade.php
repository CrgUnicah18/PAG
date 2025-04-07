@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">📢 Mis Notificaciones</h1>


        <div class="bg-white shadow-lg rounded-lg p-4">
            @forelse($notificaciones as $notificacion)


                <div class="border-b last:border-none py-4 flex justify-between items-center">
                    <div>
                        <p class="text-lg font-semibold text-gray-700">{{ $notificacion->data['mensaje'] }}</p>
                        @if(isset($notificacion->data['fecha_inicio']) && isset($notificacion->data['fecha_fin']))
                            <p class="text-sm text-gray-500">
                                Desde: {{ $notificacion->data['fecha_inicio'] }} |
                                Hasta: {{ $notificacion->data['fecha_fin'] }}
                            </p>
                        @endif


                    </div>
                    <a href="{{ route('empleado.notificaciones.leer', $notificacion->id) }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-4 py-2 rounded-lg shadow-md transition-all">
                        📄 Revisar mi Solicitud
                    </a>
                </div>
            @empty
                <p class="text-center text-gray-500 py-6">🚀 No tienes notificaciones en este momento.</p>
            @endforelse
        </div>
    </div>
@endsection