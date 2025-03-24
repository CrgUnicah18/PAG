<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPermiso extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'dias', 'es_vacacion', 'es_licencia', 'es_licenciam', 'requiere_subsidio'];

    // Relación con Vacacion (una tipo de permiso puede estar en muchas vacaciones)
    public function vacaciones()
    {
        return $this->hasMany(Vacacion::class, 'tipo_permiso_id');
    }

    // Relación con Permiso (una tipo de permiso puede estar en muchos permisos)
    public function permisos()
    {
        return $this->hasMany(Permiso::class, 'tipo_permiso_id');
    }

}
