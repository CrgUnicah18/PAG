<?php

// app/Models/Reaccion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaccion extends Model
{
    use HasFactory;
    protected $table = 'reacciones';


    protected $fillable = [
        'empleado_id',
        'anuncio_id',
        'visto',
    ];

    // Relación con el modelo Empleado
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id'); // Relación con la tabla empleados
    }

    // Relación con el modelo Anuncio
    public function anuncio()
    {
        return $this->belongsTo(Anuncio::class);
    }
}
