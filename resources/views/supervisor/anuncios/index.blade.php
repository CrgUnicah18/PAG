@extends('layouts.app') <!-- O cualquier layout general que utilices -->

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">👋 Bienvenido, {{ auth()->user()->name }}</h1>

        @forelse($anuncios as $anuncio)
            <div class="bg-white shadow-md rounded-2xl p-6 mb-6 border border-gray-200">
                <h2 class="text-2xl font-semibold text-blue-700 mb-2">{{ $anuncio->titulo }}</h2>
                <p class="text-gray-700 mb-4">{{ $anuncio->contenido }}</p>

                <div class="text-sm text-gray-600 mb-2">
                    <span class="font-semibold">📌 Prioridad:</span>
                    @if($anuncio->prioridad === 'alta')
                        <span class="inline-block bg-red-500 text-white px-3 py-1 rounded-full text-sm">Alta</span>
                    @elseif($anuncio->prioridad === 'media')
                        <span class="inline-block bg-yellow-500 text-white px-3 py-1 rounded-full text-sm">Media</span>
                    @elseif($anuncio->prioridad === 'baja')
                        <span class="inline-block bg-green-500 text-white px-3 py-1 rounded-full text-sm">Baja</span>
                    @else
                        <span class="inline-block bg-gray-400 text-white px-3 py-1 rounded-full text-sm">Desconocida</span>
                    @endif
                </div>

                <div class="text-sm text-gray-600 mb-4">
                    <span class="font-semibold">🕒 Publicado el:</span>
                    {{ \Carbon\Carbon::parse($anuncio->fecha_hora)->format('d/m/Y h:i A') }}
                </div>

                <!-- Botón de Reacción con ícono de 'Me gusta' -->
                @if(!$anuncio->reactions->contains('empleado_id', auth()->user()->empleado->id))
                    <form action="{{ route('supervisor.anuncios.react', $anuncio->id) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-600">
                            <i class="fas fa-heart text-xl"></i> <!-- Ícono de corazón -->
                        </button>
                    </form>
                @else
                    <span class="text-green-500 font-semibold">Ya has reaccionado a este anuncio</span>
                @endif
            </div>
        @empty
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded-md">
                No hay anuncios disponibles por ahora.
            </div>
        @endforelse
        <div class="pagination">
            {{ $anuncios->links() }}
        </div>

    </div>
@endsection