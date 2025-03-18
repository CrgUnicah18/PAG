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
use App\Http\Controllers\OficinaController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UserController;
use App\Console\Commands\ActualizarEstadoEmpleado;
use App\Http\Controllers\AnuncioController;

Artisan::command('empleados:actualizar-estado', function () {
    // Aquí va la lógica de tu comando (o bien ya está en el archivo de comando que creaste)
    $this->call(ActualizarEstadoEmpleado::class);
})->describe('Actualizar el estado de los empleados a activo si han finalizado su permiso');

Route::resource('anuncios', AnuncioController::class);

// Rutas de login y registro
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');
// Ruta de logout (POST)
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Ruta para mostrar el formulario de registro
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');

// Ruta para procesar el formulario de registro
Route::post('register', [RegisterController::class, 'register'])->name('register.submit');


// Ruta para crear el usuario (después de crear el empleado)
Route::get('create-user/{empleado_id}', [UserController::class, 'create'])->name('usuario.create');
Route::post('create-user/{empleado_id}', [UserController::class, 'store'])->name('usuario.store');

Route::get('/admin/create/{empleado_id}', [EmpleadoController::class, 'createUsuario'])
    ->name('admin.createUsuario')
    ->middleware(['auth', 'admin']); // Agregamos el middleware directamente a la ruta

Route::post('/admin/store/{empleado_id}', [EmpleadoController::class, 'storeUsuario'])
    ->name('admin.storeUsuario')
    ->middleware(['auth', 'admin']); // Lo mismo aquí

// Rutas de administración protegidas por el middleware 'auth' y 'admin'
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {

    // Página principal de admin
    Route::get('/inicio', [InicioController::class, 'index'])->name('inicio.home');

    Route::get('/anuncios', [AnuncioController::class, 'index'])->name('anuncios.index');
    Route::get('/anuncios/crear', [AnuncioController::class, 'create'])->name('anuncios.create');
    Route::post('/anuncios', [AnuncioController::class, 'store'])->name('anuncios.store');

    // Rutas para empleados
    Route::resource('empleados', EmpleadoController::class);

    Route::get('/reporte', [EmpleadoController::class, 'mostrarFormularioReporte'])->name('empleados.mostrarFormularioReporte');
    Route::post('/empleados', [EmpleadoController::class, 'storeEmpleado'])->name('empleados.storeEmpleado');
    Route::post('empleados/generar-reporte', [EmpleadoController::class, 'generarReporte'])->name('empleados.generarReporte');
    Route::post('empleados/generar-reporte-excel', [EmpleadoController::class, 'generarReporteExcel'])->name('empleados.generarReporteExcel');


    // Rutas para permisos
    Route::resource('permisos', PermisoController::class);
    Route::post('/permisos/{permiso}/comentar', [PermisoController::class, 'comentar'])->name('permisos.comentar');
    Route::post('permisos/{id}/aprobar', [PermisoController::class, 'aprobar'])->name('permisos.aprobar');
    Route::post('permisos/{id}/declinar', [PermisoController::class, 'declinar'])->name('permisos.declinar');

    // Rutas para vacaciones
    Route::get('vacaciones', [VacacionController::class, 'index'])->name('vacaciones.index');
    Route::get('vacaciones/create', [VacacionController::class, 'create'])->name('vacaciones.create');
    Route::post('vacaciones', [VacacionController::class, 'store'])->name('vacaciones.store');
    Route::post('vacaciones/aprobar/{vacacion}', [VacacionController::class, 'aprobar'])->name('vacaciones.aprobar');
    Route::post('vacaciones/declinar/{vacacion}', [VacacionController::class, 'rechazar'])->name('vacaciones.declinar');
    Route::post('vacaciones/addComentario/{vacacion}', [VacacionController::class, 'addComentario'])->name('vacaciones.addComentario');

    // Ruta para mostrar el formulario de filtros del reporte de vacaciones
    Route::get('vacaciones/reporte', [VacacionController::class, 'mostrarFormularioReporte'])->name('vacaciones.reporte');

    // Ruta para generar el reporte con los filtros seleccionados
    Route::get('vacaciones/generar-reporte', [VacacionController::class, 'generarReporte'])->name('vacaciones.generar-reporte');

    // Ruta para exportar a PDF
    Route::get('vacaciones/exportar-pdf', [VacacionController::class, 'exportarPDF'])->name('vacaciones.exportarPDF');

    // Ruta para exportar a Excel
    Route::get('vacaciones/exportar-excel', [VacacionController::class, 'exportarExcel'])->name('vacaciones.exportarExcel');

    // Rutas para configuración
    Route::get('configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::prefix('configuracion')->name('configuracion.')->group(function () {
        Route::resource('tipos-permisos', TipoPermisoController::class);
        Route::get('/oficinas', [OficinaController::class, 'index'])->name('oficinas.index');
        Route::get('/crear-oficina', [OficinaController::class, 'create'])->name('crear_oficina.create');
        Route::post('/crear-oficina', [OficinaController::class, 'store'])->name('store_oficina.store');

        // Rutas para grupos
        Route::get('listar-grupos', [GrupoController::class, 'index'])->name('crear_grupo.index');
        Route::get('/crear-grupo', [GrupoController::class, 'create'])->name('crear_grupo.create');
        Route::post('/crear-grupo', [GrupoController::class, 'store'])->name('store_grupo.store');

        // Rutas para tipos de contrato
        Route::resource('tipos-contratos', TiposContratoController::class)->parameters([
            'tipos-contratos' => 'tipoContrato', // Cambiar el nombre del parámetro a 'tipoContrato'
        ]);

        // Rutas para eliminar empleados
        Route::get('eliminar-empleado', [EmpleadoEliminarController::class, 'index'])->name('eliminar-empleado.index');
        Route::delete('eliminar-empleado/{id}', [EmpleadoEliminarController::class, 'destroy'])->name('eliminar-empleado.destroy');
    });
});

// Rutas para supervisor
Route::middleware(['auth', 'isAdmin'])->prefix('supervisor')->name('supervisor.')->group(function () {

    // Página principal de supervisor
    Route::get('/inicio', [InicioController::class, 'index'])->name('inicio.home');

    Route::get('/anuncios', [AnuncioController::class, 'index'])->name('anuncios.index');

    // Rutas para empleados (solo vista de empleados, no creación ni edición)
    Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
    Route::get('/empleados/{empleado}', [EmpleadoController::class, 'show'])->name('empleados.show');

    // Rutas para permisos (puede incluir aprobación/rechazo, si es necesario)
    Route::resource('permisos', PermisoController::class);
    Route::post('/permisos/{permiso}/addComentario', [PermisoController::class, 'addComentario'])->name('permisos.addComentario');
    Route::post('permisos/{id}/aprobar', [PermisoController::class, 'aprobar'])->name('permisos.aprobar');
    Route::post('permisos/{id}/declinar', [PermisoController::class, 'declinar'])->name('permisos.declinar');

    // Rutas para vacaciones (gestión similar a permisos)
    Route::resource('vacaciones', VacacionController::class);
    Route::post('vacaciones/{vacacion}/addComentario', [VacacionController::class, 'addComentario'])->name('vacaciones.addComentario');
    Route::post('vacaciones/aprobar/{vacacion}', [VacacionController::class, 'aprobar'])->name('vacaciones.aprobar');
    Route::post('vacaciones/declinar/{vacacion}', [VacacionController::class, 'rechazar'])->name('vacaciones.declinar');
});


// Rutas para empleado
Route::middleware(['auth', 'isAdmin'])->prefix('empleado')->name('empleado.')->group(function () {

    // Página principal de supervisor
    Route::get('/inicio', [InicioController::class, 'index'])->name('inicio.home');

    Route::get('/anuncios', [AnuncioController::class, 'index'])->name('anuncios.index');
    // Ruta para reaccionar a un anuncio
    Route::post('/anuncios/{anuncio}/reaccionar', [AnuncioController::class, 'reactToAnuncio'])->name('anuncios.react');

    // Rutas para empleados (solo vista de empleados, no creación ni edición)
    Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
    //Route::get('/empleados/{empleado}', [EmpleadoController::class, 'show'])->name('empleados.show');

    // Rutas para permisos (puede incluir aprobación/rechazo, si es necesario)
    Route::resource('permisos', PermisoController::class);
    Route::post('/permisos/{permiso}/addComentario', [PermisoController::class, 'addComentario'])->name('permisos.addComentario');
    Route::post('permisos/{id}/aprobar', [PermisoController::class, 'aprobar'])->name('permisos.aprobar');
    Route::post('permisos/{id}/declinar', [PermisoController::class, 'declinar'])->name('permisos.declinar');

    // Rutas para vacaciones (gestión similar a permisos)
    Route::resource('vacaciones', VacacionController::class);
    Route::post('vacaciones/{vacacion}/addComentario', [VacacionController::class, 'addComentario'])->name('vacaciones.addComentario');
    Route::post('vacaciones/aprobar/{vacacion}', [VacacionController::class, 'aprobar'])->name('vacaciones.aprobar');
    Route::post('vacaciones/declinar/{vacacion}', [VacacionController::class, 'declinar'])->name('vacaciones.declinar');
});

