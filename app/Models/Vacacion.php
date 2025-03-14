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
    public function setEstadoAttribute($value)
    {
        $this->attributes['estado'] = $value;

        // Si el estado cambia a "rechazada", restauramos los días de vacaciones
        if ($value == 'rechazadas') {
            $empleado = $this->empleado;

            // Calcular los días de las vacaciones rechazadas
            $duracionRechazada = $this->calcularDuracion($this->fecha_inicio, $this->fecha_fin);

            // Restauramos los días de vacaciones
            $empleado->vacaciones_restantes += $duracionRechazada;

            $empleado->save();
        }
    }
    public function calcularDuracion()
    {
        $fechaInicio = \Carbon\Carbon::parse($this->fecha_inicio);  // Convierte a objeto Carbon
        $fechaFin = \Carbon\Carbon::parse($this->fecha_fin);        // Convierte a objeto Carbon

        // Calcula la diferencia en días
        return $fechaInicio->diffInDays($fechaFin);
    }

}
