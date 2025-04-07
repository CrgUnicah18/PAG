<?php

namespace App\Exports;

use App\Models\Vacacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class VacacionesExport implements FromCollection, WithHeadings, WithEvents
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        // Filtra las vacaciones según los parámetros
        $query = Vacacion::query();

        // Filtrar por empleado si se seleccionó
        if ($this->request->filled('empleado_id')) {
            $query->where('empleado_id', $this->request->empleado_id);
        }

        // Filtrar por fechas si se seleccionaron
        if ($this->request->filled('fecha_inicio') && $this->request->filled('fecha_fin')) {
            $query->whereBetween('fecha_inicio', [$this->request->fecha_inicio, $this->request->fecha_fin]);
        }

        // Filtrar por estado si se seleccionó
        if ($this->request->filled('estado')) {
            $query->whereIn('estado', $this->request->estado);
        }

        // Obtener todas las vacaciones filtradas
        $vacaciones = $query->get();

        // Crear un arreglo con los datos a exportar, basado en los campos seleccionados
        $exportData = $vacaciones->map(function ($vacacion) {
            $data = [];

            // Verificar qué campos se han seleccionado y agregar solo esos
            if (in_array('empleado_id', $this->request->campos)) {
                $data['empleado_id'] = $vacacion->empleado->nombre . ' ' . $vacacion->empleado->apellido;
            }

            if (in_array('tipo_permiso_id', $this->request->campos)) {
                $data['tipo_permiso_id'] = $vacacion->tipoPermiso->nombre;
            }

            if (in_array('fecha_inicio', $this->request->campos)) {
                $data['fecha_inicio'] = Carbon::parse($vacacion->fecha_inicio)->format('Y-m-d');
            }

            if (in_array('fecha_fin', $this->request->campos)) {
                $data['fecha_fin'] = Carbon::parse($vacacion->fecha_fin)->format('Y-m-d');
            }

            if (in_array('duracion_dias', $this->request->campos)) {
                $data['duracion_dias'] = $vacacion->duracion_dias;
            }

            // Agregar siempre la columna "estado", aunque no se haya seleccionado
            $data['estado'] = $vacacion->estado;

            if (in_array('comentario', $this->request->campos)) {
                $data['comentario'] = $vacacion->comentario;
            }

            if (in_array('periodo', $this->request->campos)) {
                $data['periodo'] = $vacacion->periodo;
            }

            return $data;
        });

        return $exportData;
    }

    public function headings(): array
    {
        // Crear un arreglo con los encabezados, basados en los campos seleccionados
        $headings = [];

        // Verificar qué campos se han seleccionado y agregar los encabezados correspondientes
        if (in_array('empleado_id', $this->request->campos)) {
            $headings[] = 'Empleado';
        }

        if (in_array('tipo_permiso_id', $this->request->campos)) {
            $headings[] = 'Tipo de Permiso';
        }

        if (in_array('fecha_inicio', $this->request->campos)) {
            $headings[] = 'Fecha de Inicio';
        }

        if (in_array('fecha_fin', $this->request->campos)) {
            $headings[] = 'Fecha de Fin';
        }

        if (in_array('duracion_dias', $this->request->campos)) {
            $headings[] = 'Duración (días)';
        }

        // Siempre agregar "Estado" al encabezado
        $headings[] = 'Estado';

        if (in_array('comentario', $this->request->campos)) {
            $headings[] = 'Comentario';
        }

        if (in_array('periodo', $this->request->campos)) {
            $headings[] = 'Periodo';
        }

        // Estilo para los encabezados
        $headings = array_map(function ($heading) {
            return strtoupper($heading); // Poner los encabezados en mayúsculas
        }, $headings);

        return $headings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $query = Vacacion::query();

                // Filtrar por empleado si se seleccionó
                if ($this->request->filled('empleado_id')) {
                    $query->where('empleado_id', $this->request->empleado_id);
                }

                // Obtener todas las vacaciones filtradas
                $vacaciones = $query->get();

                // Calcular totales
                $totalVacacionesRestantes = $this->request->filled('empleado_id')
                    ? $vacaciones->first()->empleado->vacaciones_restantes ?? 'N/A'
                    : 'N/A';

                $totalDiasAprobados = $vacaciones->where('estado', 'aprobadas')->sum('duracion_dias');

                // Agregar los totales al final de la hoja
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow() + 2; // Obtener la última fila y dejar un espacio
    
                $sheet->setCellValue("A{$lastRow}", 'Totales:');
                $sheet->setCellValue("A" . ($lastRow + 1), 'Total de Vacaciones Restantes:');
                $sheet->setCellValue("B" . ($lastRow + 1), $totalVacacionesRestantes);
                $sheet->setCellValue("A" . ($lastRow + 2), 'Total de Vacaciones Aprobadas (Duración en días):');
                $sheet->setCellValue("B" . ($lastRow + 2), $totalDiasAprobados);
            },
        ];
    }
}
