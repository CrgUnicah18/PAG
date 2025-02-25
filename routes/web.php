<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\TipoPermisoController;
use App\Http\Controllers\EmpleadoEliminarController;
use App\Http\Controllers\PermisoController;
use App\Http\Controllers\VacacionController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\TiposContratoController;
use App\Models\Vacacion;

Route::prefix('admin')->name('admin.')->group(function () {

    // Página principal
    Route::get('/inicio', [InicioController::class, 'index'])->name('inicio.home');

    // Rutas para empleados
    Route::resource('empleados', EmpleadoController::class);
    //Rutas para permisos
    Route::resource('permisos', PermisoController::class);

    Route::post('/permisos/{permiso}/addComentario', [PermisoController::class, 'addComentario'])->name('permisos.addComentario');

    // Rutas específicas para aprobar o declinar permisos
    Route::post('permisos/{id}/aprobar', [PermisoController::class, 'aprobar'])->name('permisos.aprobar');
    Route::post('permisos/{id}/declinar', [PermisoController::class, 'declinar'])->name('permisos.declinar');

    Route::resource('vacaciones', VacacionController::class); // Para todas las acciones CRUD
    // Ruta para mostrar el formulario de asignación de vacaciones
    Route::get('vacaciones/create', [VacacionController::class, 'create'])->name('vacaciones.create');

    // Ruta para guardar las vacaciones asignadas
    Route::post('vacaciones', [VacacionController::class, 'store'])->name('vacaciones.store');

    Route::post('vacaciones/aprobar/{vacacion}', [VacacionController::class, 'aprobar'])->name('vacaciones.aprobar');
    Route::post('vacaciones/declinar/{vacacion}', [VacacionController::class, 'declinar'])->name('vacaciones.declinar');
    Route::post('vacaciones/{vacacion}/addComentario', [VacacionController::class, 'addComentario'])->name('vacaciones.addComentario');


    // Ruta para configuración, que usará el ConfiguracionController
    Route::get('configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');

    // Rutas para configuración (con las subrutas para tipos-permisos y eliminar-empleados)
    Route::prefix('configuracion')->name('configuracion.')->group(function () {
        // Rutas para tipos de permisos
        Route::resource('tipos-permisos', TipoPermisoController::class);

        // Rutas para tipos de contrato
        Route::resource('tipos-contratos', TiposContratoController::class)->parameters([
            'tipos-contratos' => 'tipoContrato', // Cambiar el nombre del parámetro a 'tipoContrato'
        ]);



        // Rutas para eliminar empleados
        Route::get('eliminar-empleado', [EmpleadoEliminarController::class, 'index'])
            ->name('eliminar-empleado.index');

        Route::delete('eliminar-empleado/{id}', [EmpleadoEliminarController::class, 'destroy'])
            ->name('eliminar-empleado.destroy');
    });
});
