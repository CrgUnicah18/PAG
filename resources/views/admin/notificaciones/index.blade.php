@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4 text-center text-2xl font-semibold">Notificaciones de Solicitudes</h1>

        @forelse($notificaciones as $notificacion)
            <div class="card mb-3 shadow-lg">
                <div class="card-header bg-blue-500 text-white font-bold">
                    Notificación de Vacaciones
                </div>
                <div class="card-body bg-gray-100">
                    <p><strong>Mensaje:</strong> {{ $notificacion->data['mensaje'] }}</p>
                    <p><strong>Fecha de Inicio:</strong>
                        {{ \Carbon\Carbon::parse($notificacion->data['fecha_inicio'])->toFormattedDateString() }}</p>
                    <p><strong>Fecha de Fin:</strong>
                        {{ \Carbon\Carbon::parse($notificacion->data['fecha_fin'])->toFormattedDateString() }}</p>
                    <div class="text-center">
                        <!-- El enlace que ahora llevará a admin.vacaciones.index -->
                        <a href="{{ route('admin.notificaciones.leer', $notificacion->id) }}" class="btn btn-primary">Revisar
                            Solicitud</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info text-center">
                <strong>No hay nuevas notificaciones.</strong>
            </div>
        @endforelse
    </div>
@endsection