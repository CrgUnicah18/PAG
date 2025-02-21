<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\TipoPermisoController;
use App\Http\Controllers\EmpleadoEliminarController;
use App\Http\Controllers\PermisoController;

// Página principal
Route::get('/', function () {
    return view('home');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // Rutas para empleados
    Route::resource('empleados', EmpleadoController::class);
    //Rutas para permisos
    Route::resource('permisos', PermisoController::class);

    // Rutas específicas para aprobar o declinar permisos
    Route::post('permisos/{id}/aprobar', [PermisoController::class, 'aprobar'])->name('permisos.aprobar');
    Route::post('permisos/{id}/declinar', [PermisoController::class, 'declinar'])->name('permisos.declinar');

    // Ruta para configuración, que usará el ConfiguracionController
    Route::get('configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');

    // Rutas para configuración (con las subrutas para tipos-permisos y eliminar-empleados)
    Route::prefix('configuracion')->name('configuracion.')->group(function () {
        // Rutas para tipos de permisos
        Route::resource('tipos-permisos', TipoPermisoController::class);

        // Rutas para eliminar empleados
        Route::get('eliminar-empleado', [EmpleadoEliminarController::class, 'index'])
            ->name('eliminar-empleado.index');

        Route::delete('eliminar-empleado/{id}', [EmpleadoEliminarController::class, 'destroy'])
            ->name('eliminar-empleado.destroy');
    });
});
