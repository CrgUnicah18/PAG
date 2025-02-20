<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\TipoPermisoController;
use App\Http\Controllers\EmpleadoEliminarController;

// Página principal
Route::get('/', function () {
    return view('home');
});

// Rutas del módulo de empleados
Route::resource('empleados', EmpleadoController::class);

// Rutas del módulo de configuración
Route::prefix('configuracion')->name('configuracion.')->group(function () {
    Route::get('/', [ConfiguracionController::class, 'index'])->name('index');
});

// Rutas para TipoPermisoController dentro de 'configuracion/tipos-permisos'
Route::resource('configuracion/tipos-permisos', TipoPermisoController::class)->names('configuracion.tipos-permisos');
Route::resource('configuracion/eliminar-empleado', EmpleadoEliminarController::class)
    ->only(['index', 'destroy'])
    ->names('configuracion.eliminar-empleado');
