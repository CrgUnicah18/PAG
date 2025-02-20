<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'apellido',
        'direccion',
        'telefono',
        'fecha_nacimiento',
        'oficina_id',
        'grupo_id'
    ];
    // Relación con Oficina
    public function oficina()
    {
        return $this->belongsTo(Oficina::class);
    }

    // Relación con Grupo
    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    // Relación con Permisos
    public function permisos()
    {
        return $this->hasMany(Permiso::class);
    }

    // Relación con Vacaciones
    public function vacaciones()
    {
        return $this->hasMany(Vacacion::class);
    }

    // Relación con Usuario (nuevo)
    public function user()
    {
        return $this->hasOne(User::class);
    }
    public function supervisor()
    {
        return $this->belongsTo(Empleado::class, 'supervisor_id');
    }

}
