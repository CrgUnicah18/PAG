@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">📢 Panel de Administración - Anuncios</h1>

        <!-- Botón para Crear un Nuevo Anuncio -->
        <div class="mb-6">
            <a href="{{ route('admin.anuncios.create') }}"
                class="px-6 py-2 bg-green-600 text-white rounded-full hover:bg-green-700">
                <i class="fas fa-plus-circle mr-2"></i> Crear Anuncio
            </a>
        </div>

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

                {{-- Mostrar el conteo de reacciones --}}
                <div class="text-sm text-gray-600 mb-4">
                    <span class="font-semibold">💬 Reacciones:</span>
                    <span class="text-gray-800">{{ $anuncio->conteo_reacciones }}</span>
                </div>

                {{-- Mostrar a quién se le asignó el anuncio --}}
                <div class="bg-gray-100 p-4 rounded-lg mt-4">
                    <h3 class="font-semibold text-gray-700 mb-2">👥 Asignado a:</h3>

                    @if($anuncio->audiencia === 'empresa')
                        <p class="text-gray-600">Toda la empresa 🏢</p>
                    @else
                        @if($anuncio->oficinas->count() > 0)
                            <p class="text-gray-600 mb-1">
                                <strong>Oficinas:</strong>
                                {{ $anuncio->oficinas->pluck('nombre')->implode(', ') }}
                            </p>
                        @endif

                        @if($anuncio->grupos->count() > 0)
                            <p class="text-gray-600">
                                <strong>Grupos:</strong>
                                {{ $anuncio->grupos->pluck('nombre')->implode(', ') }}
                            </p>
                        @endif
                    @endif
                </div>
            </div>

        @empty
            <div class="bg-yellow-100 text-yellow-800 p-4 rounded-md">
                No hay anuncios creados aún.
            </div>
        @endforelse
        <div class="pagination">
            {{ $anuncios->links() }}
        </div>

    </div>
@endsection