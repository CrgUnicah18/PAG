<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'oficina_id'];
    //Relaciones
    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }

}
