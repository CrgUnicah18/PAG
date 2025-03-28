@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Mis Notificaciones</h1>
        <ul>
            @forelse($notificaciones as $notificacion)
                <li>
                    <p>{{ $notificacion->data['mensaje'] }}</p>
                    <p>Desde: {{ $notificacion->data['fecha_inicio'] }} | Hasta: {{ $notificacion->data['fecha_fin'] }}</p>
                    <a href="{{ $notificacion->data['link'] }}" class="btn btn-primary">Ver Solicitud</a>
                </li>
            @empty
                <li>No tienes notificaciones en este momento.</li>
            @endforelse
        </ul>
    </div>
@endsection