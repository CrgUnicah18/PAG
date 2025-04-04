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
        'cargo',
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
        $fechaIngreso = Carbon::parse($this->fecha_ingreso); // Fecha de ingreso del empleado
        $fechaActual = Carbon::now(); // Fecha actual
        $diasTotales = 0; // Total de días legales que puede tomar en el año
        $vacacionesRestantes = 0; // Días disponibles acumulados o completos

        // Calcular los años trabajados
        $anosTrabajados = $fechaIngreso->diffInYears($fechaActual);

        \Log::info("Años trabajados: " . $anosTrabajados);

        // Lógica según los años trabajados
        if ($anosTrabajados < 1) {
            // Empleado con menos de un año de trabajo
            $mesIngreso = $fechaIngreso->month; // Mes de ingreso
            $mesActual = $fechaActual->month; // Mes actual
            $mesesTrabajados = $mesActual - $mesIngreso; // Meses trabajados desde el ingreso
            $mesesRestantesDelAno = 12 - $mesIngreso; // Meses restantes del año desde el ingreso

            // Vacaciones proporcionales hasta diciembre
            $diasTotales = round(($mesesRestantesDelAno / 12) * 10); // Total de días legales hasta diciembre
            $vacacionesRestantes = round(($mesesTrabajados / 12) * 10); // Días acumulados hasta el mes actual

            \Log::info("Meses trabajados: " . $mesesTrabajados);
            \Log::info("Meses restantes del año: " . $mesesRestantesDelAno);
            \Log::info("Vacaciones proporcionales acumuladas: " . $vacacionesRestantes);
        } elseif ($anosTrabajados >= 1 && $anosTrabajados < 2) {
            // Empleado con 1 año de trabajo
            $diasTotales = 10; // Vacaciones completas
            $vacacionesRestantes = $diasTotales; // Disponibilidad completa
        } elseif ($anosTrabajados >= 2 && $anosTrabajados < 3) {
            $diasTotales = 12; // Vacaciones completas
            $vacacionesRestantes = $diasTotales;
        } elseif ($anosTrabajados >= 3 && $anosTrabajados < 4) {
            $diasTotales = 15; // Vacaciones completas
            $vacacionesRestantes = $diasTotales;
        } else {
            $diasTotales = 20; // Vacaciones completas
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

        // Actualizar los campos del modelo
        $this->vacaciones_restantes = $vacacionesRestantes;
        $this->vacaciones_tomadas = $diasTotales; // Total de días legales del año
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
