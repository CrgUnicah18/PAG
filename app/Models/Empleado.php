<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB; // Para la clase DB
use DateTime; // Para la clase DateTime
use Illuminate\Support\Facades\Log;



class Empleado extends Model
{
    use HasFactory;
    use HasRoles;

    protected $fillable = [
        'nombre',
        'apellido',
        'genero',
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
        'documento_contrato',
        'vacaciones_restantes'
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
    // En el modelo Empleado
    public function vacaciones()
    {
        return $this->hasMany(Vacacion::class, 'empleado_id');
    }
    public function tipoContrato()
    {
        return $this->belongsTo(TipoContrato::class);
    }

    // Relación con User
    // En el modelo Empleado
    public function user()
    {
        return $this->hasOne(User::class);  // Relación inversa, si es necesario.
    }
    public function supervisor()
    {
        return $this->belongsTo(Empleado::class, 'supervisor_id');
    }
    public function reacciones()
    {
        return $this->hasMany(Reaccion::class, 'empleado_id');
    }

    public function calcularBalanceVacaciones()
    {
        $fechaIngreso = new DateTime($this->fecha_ingreso);
        $fechaActual = new DateTime();

        $intervalo = $fechaIngreso->diff($fechaActual);
        $anosTrabajados = $intervalo->y;

        \Log::info("Años trabajados: " . $anosTrabajados);

        if ($anosTrabajados < 1) {
            $vacacionesRestantes = 5;
        } elseif ($anosTrabajados >= 1 && $anosTrabajados < 2) {
            $vacacionesRestantes = 10;
        } elseif ($anosTrabajados >= 2 && $anosTrabajados < 3) {
            $vacacionesRestantes = 12;
        } elseif ($anosTrabajados >= 3 && $anosTrabajados < 4) {
            $vacacionesRestantes = 15;
        } else {
            $vacacionesRestantes = 20;
        }

        \Log::info("Vacaciones Restantes Iniciales: " . $vacacionesRestantes);

        // Aquí calculamos solo las vacaciones que NO están rechazadas
        $vacacionesNoRechazadas = $this->vacaciones()
            ->whereIn('estado', ['aprobadas', 'pendiente', 'pendientes_aprobacion'])
            ->sum(DB::raw('DATEDIFF(fecha_fin, fecha_inicio) + 1'));

        \Log::info("Vacaciones No Rechazadas (sumadas): " . $vacacionesNoRechazadas);

        // Restar solo las vacaciones que NO estén rechazadas
        $vacacionesRestantes -= min($vacacionesNoRechazadas, $vacacionesRestantes);

        \Log::info("Vacaciones Restantes después de la resta: " . $vacacionesRestantes);

        // Asegúrate de que no se sumen más días de los disponibles
        $vacacionesRestantes = max($vacacionesRestantes, 0);

        // Guardar el saldo calculado en el campo 'vacaciones_restantes'
        $this->vacaciones_restantes = $vacacionesRestantes;
        $this->save();

        return $vacacionesRestantes;
    }


    public function restaurarVacaciones($dias)
    {
        // Suponiendo que $dias es la cantidad de días a restaurar
        $this->vacaciones_restantes += $dias;

        // Guardar el nuevo saldo de vacaciones
        $this->save();

        \Log::info("Vacaciones restauradas para el empleado {$this->id}. Vacaciones restantes: {$this->vacaciones_restantes}");
    }



}
