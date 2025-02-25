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
        'estado',
        'fecha_ingreso',
        'tipo_contrato',
        'vacaciones_tomadas',
        'direccion',
        'telefono',
        'fecha_nacimiento',
        'oficina_id',
        'grupo_id',
        'tipo_contrato_id',
        'foto_perfil',
        'documento_contrato'
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
    public function tipoContrato()
    {
        return $this->belongsTo(TipoContrato::class);
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

    public function calcularVacacionesDisponibles()
    {
        $fechaIngreso = $this->fecha_ingreso;
        $fechaActual = now();

        // Calcular los años de servicio
        $añosDeServicio = $fechaIngreso->diffInYears($fechaActual);

        // Inicializamos los días de vacaciones disponibles
        $vacacionesDisponibles = 0;

        // Si tiene más de 2 meses de trabajo, empieza a tener vacaciones
        if ($añosDeServicio < 1) {
            $vacacionesDisponibles = 5; // 5 días de vacaciones antes de cumplir el primer año
        } elseif ($añosDeServicio == 1) {
            $vacacionesDisponibles = 10; // 10 días al cumplir el primer año
        } elseif ($añosDeServicio == 2) {
            $vacacionesDisponibles = 12; // 12 días al cumplir el segundo año
        } elseif ($añosDeServicio == 3) {
            $vacacionesDisponibles = 15; // 15 días al cumplir el tercer año
        } elseif ($añosDeServicio == 4) {
            $vacacionesDisponibles = 20; // 20 días al cumplir el cuarto año
        } elseif ($añosDeServicio > 4) {
            // Para cada año adicional después del cuarto, sumamos 5 días más
            $vacacionesDisponibles = 20 + 5 * ($añosDeServicio - 4); // 20 días por los primeros 4 años y 5 días por cada año adicional
        }

        // Restamos las vacaciones que ya ha tomado
        return $vacacionesDisponibles - $this->vacaciones_tomadas;
    }






}
