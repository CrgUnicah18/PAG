<!-- resources/views/anuncios/index.blade.php -->

@extends('layouts.app')

@section('content')
    <h1>Lista de Anuncios</h1>
    <ul>
        @foreach ($anuncios as $anuncio)
            <li>{{ $anuncio->titulo }} - {{ $anuncio->contenido }} - Prioridad: {{ $anuncio->prioridad }}</li>
        @endforeach
    </ul>
@endsection