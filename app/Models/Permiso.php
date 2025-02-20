<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    // Propiedad fillable para los campos que puedes asignar masivamente
    protected $fillable = [
        'empleado_id',
        'tipo_permiso_id',
        'aprobado',
        'fecha_inicio',
        'fecha_fin',
    ];
    //Relacion
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
    public function tipoPermiso()
    {
        return $this->belongsTo(TipoPermiso::class, 'tipo_permiso_id');
    }


}
