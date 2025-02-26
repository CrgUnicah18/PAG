@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Generar Reporte de Empleados</h1>

        @if(session('error'))
            <div class="bg-red-500 text-white p-3 rounded-md mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.empleados.generarReporte') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <h3 class="text-lg font-semibold">Seleccione los campos que desea incluir en el reporte:</h3>
                <div class="grid grid-cols-2 gap-2 mt-2">
                    @foreach($campos as $campo => $label)
                        <div class="flex items-center">
                            <input class="form-checkbox h-5 w-5 text-blue-600" type="checkbox" name="campos[]"
                                value="{{ $campo }}" id="{{ $campo }}">
                            <label class="ml-2 text-gray-700" for="{{ $campo }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">Generar
                Reporte</button>
        </form>
    </div>
@endsection