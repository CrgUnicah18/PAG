<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoContrato extends Model
{
    use HasFactory;
    protected $table = 'tipo_contratos';  // Aquí especificas el nombre correcto de la tabla
    protected $fillable = ['nombre'];

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }
}
