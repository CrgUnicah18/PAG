<?php

namespace App\Http\Controllers;

use App\Models\TipoPermiso;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    // Muestra el menú principal de configuración
    public function index()
    {
        return view('admin.configuracion.index');
    }
    public function tiposPermisos()
    {
        $tiposPermiso = TipoPermiso::all();  // Obtenemos todos los tipos de permisos
        return view('admin.configuracion.tipos-permisos.index', compact('tiposPermiso'));  // Pasamos los datos a la vista
    }


}
