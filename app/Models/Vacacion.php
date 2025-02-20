<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacacion extends Model
{
    use HasFactory;
    //Relacion
    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

}
