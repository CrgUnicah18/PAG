<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB; // Para la clase DB
use DateTime; // Para la clase DateTime
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;



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
        'vacaciones_restantes',
        'dn',
        'dn_file',
        'supervisor_id',
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
    // Relación con Vacaciones
    public function vacaciones()
    {
        return $this->hasMany(Vacacion::class, 'empleado_id');
    }

    // Relación inversa con User (ya que User tiene un campo empleado_id)
    public function user()
    {
        return $this->hasOne(User::class, 'empleado_id');
    }
    public function tipoContrato()
    {
        return $this->belongsTo(TipoContrato::class);
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

        // Calcular los días totales de vacaciones según los años trabajados
        if ($anosTrabajados < 1) {
            $mesIngreso = (int) $fechaIngreso->format('n');
            $mesesRestantesDelAno = 12 - $mesIngreso;
            $vacacionesProporcionales = round(($mesesRestantesDelAno / 12) * 10);
            $diasTotales = 10;
            $vacacionesRestantes = $vacacionesProporcionales;
        } elseif ($anosTrabajados >= 1 && $anosTrabajados < 2) {
            $diasTotales = 10;
            $vacacionesRestantes = $diasTotales;
        } elseif ($anosTrabajados >= 2 && $anosTrabajados < 3) {
            $diasTotales = 12;
            $vacacionesRestantes = $diasTotales;
        } elseif ($anosTrabajados >= 3 && $anosTrabajados < 4) {
            $diasTotales = 15;
            $vacacionesRestantes = $diasTotales;
        } else {
            $diasTotales = 20;
            $vacacionesRestantes = $diasTotales;
        }

        \Log::info("Vacaciones Totales: " . $diasTotales);
        \Log::info("Vacaciones Restantes antes de restar: " . $vacacionesRestantes);

        // Obtener las vacaciones no rechazadas y contar solo los días hábiles
        $vacacionesNoRechazadas = $this->vacaciones()
            ->whereIn('estado', ['aprobadas', 'pendiente', 'pendientes_aprobacion'])
            ->get();

        $diasTomados = 0;

        foreach ($vacacionesNoRechazadas as $vacacion) {
            $inicio = Carbon::parse($vacacion->fecha_inicio);
            $fin = Carbon::parse($vacacion->fecha_fin);

            while ($inicio->lte($fin)) { // Mientras el inicio sea menor o igual al fin
                if ($inicio->isWeekday()) { // Solo contar lunes a viernes
                    $diasTomados++;
                }
                $inicio->addDay();
            }
        }

        \Log::info("Vacaciones No Rechazadas (solo días hábiles): " . $diasTomados);

        // Restar los días hábiles tomados de las vacaciones restantes
        $vacacionesRestantes -= min($diasTomados, $vacacionesRestantes);
        $vacacionesRestantes = max($vacacionesRestantes, 0);

        \Log::info("Vacaciones Restantes después de la resta: " . $vacacionesRestantes);

        $this->vacaciones_restantes = $vacacionesRestantes;
        $this->vacaciones_tomadas = $diasTotales;
        $this->save();

        return [
            'vacaciones_restantes' => $vacacionesRestantes,
            'vacaciones_tomadas' => $diasTotales,
        ];
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
