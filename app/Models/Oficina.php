<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oficina extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'direccion'];
    //Relacion
    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }

}
