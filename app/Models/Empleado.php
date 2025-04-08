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

        // Verificar si el empleado ha cumplido un año
        // Usamos clone para evitar modificar la fecha de ingreso original
        $fechaCumpleUnAno = $fechaIngreso->copy()->addYear(1);
        $hasCumplidoUnAno = $fechaCumpleUnAno->lte($fechaActual);

        \Log::info("Fecha de ingreso: " . $fechaIngreso->format('Y-m-d'));
        \Log::info("¿Ha cumplido un año?: " . ($hasCumplidoUnAno ? 'Sí' : 'No'));

        // Caso 1: Si no ha cumplido un año, se calcula proporcional
        if (!$hasCumplidoUnAno) {
            // Si el empleado ingresó el año pasado, contamos desde enero
            if ($fechaIngreso->year < $fechaActual->year) {
                // Calcular meses trabajados desde enero hasta la fecha actual
                $mesesTrabajados = $fechaActual->month;
                \Log::info("Meses trabajados (desde enero): " . $mesesTrabajados);
            } else {
                // Calcular meses trabajados desde el ingreso hasta la fecha actual
                $mesesTrabajados = $fechaIngreso->diffInMonths($fechaActual) + 1;
                \Log::info("Meses trabajados (desde ingreso): " . $mesesTrabajados);
            }

            $diasTotales = 10; // Base para cálculo proporcional
            $vacacionesRestantes = ceil(($mesesTrabajados / 12) * $diasTotales);
            \Log::info("Cálculo proporcional: " . $mesesTrabajados . "/12 * 10 = " . $vacacionesRestantes);
        }
        // Caso 2: Si ya cumplió un año o más, asignar días completos según antigüedad
        else {
            if ($anosTrabajados < 2) {
                $diasTotales = 10; // 10 días completos después de 1 año
                $vacacionesRestantes = 10;
            } elseif ($anosTrabajados < 3) {
                $diasTotales = 12; // 12 días completos después de 2 años
                $vacacionesRestantes = 12;
            } elseif ($anosTrabajados < 4) {
                $diasTotales = 15; // 15 días completos después de 3 años
                $vacacionesRestantes = 15;
            } else {
                $diasTotales = 20; // 20 días completos después de 4 años o más
                $vacacionesRestantes = 20;
            }
            \Log::info("Vacaciones asignadas por antigüedad: " . $vacacionesRestantes);
        }

        // Calcular los días que ya ha tomado en el año actual
        $vacacionesNoRechazadas = $this->vacaciones()
            ->whereIn('estado', ['aprobadas', 'pendiente', 'pendientes_aprobacion'])
            ->whereYear('fecha_inicio', $fechaActual->year) // Solo del año actual
            ->get();

        $diasTomados = 0;

        // Contar los días hábiles tomados
        foreach ($vacacionesNoRechazadas as $vacacion) {
            $inicio = Carbon::parse($vacacion->fecha_inicio);
            $fin = Carbon::parse($vacacion->fecha_fin);

            \Log::info("Solicitud de Vacaciones: De " . $vacacion->fecha_inicio . " a " . $vacacion->fecha_fin);

            while ($inicio->lte($fin)) {
                if ($inicio->isWeekday()) {
                    $diasTomados++;
                }
                $inicio->addDay();
            }
        }

        \Log::info("Total de Días Tomados en " . $fechaActual->year . ": " . $diasTomados);

        // Restar los días tomados de las vacaciones restantes
        $vacacionesRestantes -= $diasTomados;
        $vacacionesRestantes = max($vacacionesRestantes, 0); // Evitar valores negativos

        \Log::info("Vacaciones Anuales Totales: " . $diasTotales);
        \Log::info("Vacaciones Restantes: " . $vacacionesRestantes);

        // Actualizar los campos del modelo
        $this->vacaciones_restantes = $vacacionesRestantes;
        $this->vacaciones_tomadas = $diasTomados; // Días tomados este año
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
