<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacacion extends Model
{
    use HasFactory;
    // Especifica el nombre de la tabla si no es el plural del modelo
    protected $table = 'vacaciones';
    protected $fillable = ['empleado_id', 'fecha_inicio', 'fecha_fin', 'duracion_dias', 'estado', 'tipo_permiso_id', 'comentario'];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    // Relación inversa con TipoPermiso
    public function tipoPermiso()
    {
        return $this->belongsTo(TipoPermiso::class, 'tipo_permiso_id');
    }
}
