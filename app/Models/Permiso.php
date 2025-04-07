<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Permiso extends Model
{
    use HasFactory;

    protected $fillable = [
        'empleado_id',
        'tipo_permiso_id',
        'estado',
        'fecha_inicio',
        'fecha_fin',
        'comentario',
        'subsidio_archivo',
        'reintegro',
        'periodo',
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }
    // Aseguramos que el periodo esté siempre actualizado
    public function setPeriodoAttribute($value)
    {
        $this->attributes['periodo'] = $value ?? date('Y');  // Si no se pasa un valor, usamos el año actual
    }

    public function tipoPermiso()
    {
        return $this->belongsTo(TipoPermiso::class, 'tipo_permiso_id');
    }

    // Calcular la duración del permiso en días a partir de las fechas seleccionadas
    public function calcularDuracion()
    {
        $fechaInicio = Carbon::parse($this->fecha_inicio);
        $fechaFin = Carbon::parse($this->fecha_fin);

        // Calcular la duración en días (inclusive ambos días)
        return $fechaInicio->diffInDays($fechaFin) + 1;
    }

    // Validar que la duración no exceda la cantidad de días permitidos por el tipo de permiso
    public function validarDuracionPermitida()
    {
        $duracion = $this->calcularDuracion();
        $tipoPermiso = $this->tipoPermiso;

        if ($duracion > $tipoPermiso->dias) {
            return false; // La duración solicitada es mayor que la permitida para este tipo de permiso
        }

        return true;
    }
}
