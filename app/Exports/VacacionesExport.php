<?php

namespace App\Exports;

use App\Models\Vacacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class VacacionesExport implements FromCollection, WithHeadings, WithEvents, WithCustomStartCell
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Vacacion::query();

        if ($this->request->filled('empleado_id')) {
            $query->where('empleado_id', $this->request->empleado_id);
        }

        if ($this->request->filled('fecha_inicio') && $this->request->filled('fecha_fin')) {
            $query->whereBetween('fecha_inicio', [$this->request->fecha_inicio, $this->request->fecha_fin]);
        }

        if ($this->request->filled('estado')) {
            $query->whereIn('estado', $this->request->estado);
        }

        $vacaciones = $query->get();

        return $vacaciones->map(function ($vacacion) {
            return [
                'empleado' => $vacacion->empleado->nombre . ' ' . $vacacion->empleado->apellido,
                'tipo_permiso' => $vacacion->tipoPermiso->nombre,
                'fecha_inicio' => Carbon::parse($vacacion->fecha_inicio)->format('Y-m-d'),
                'fecha_fin' => Carbon::parse($vacacion->fecha_fin)->format('Y-m-d'),
                'duracion_dias' => $vacacion->duracion_dias,
                'estado' => $vacacion->estado,
                'comentario' => $vacacion->comentario,
                'periodo' => $vacacion->periodo,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'EMPLEADO',
            'TIPO DE PERMISO',
            'FECHA DE INICIO',
            'FECHA DE FIN',
            'DURACIÓN (DÍAS)',
            'ESTADO',
            'COMENTARIO',
            'PERIODO',
        ];
    }

    public function startCell(): string
    {
        return 'A6'; // Los datos empiezan después del encabezado y el logo
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Insertar el logo
                $logoPath = public_path('images/logopag2.png');
                $drawing = new Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo de la empresa');
                $drawing->setPath($logoPath);
                $drawing->setHeight(80);
                $drawing->setCoordinates('A1');
                $drawing->setWorksheet($sheet);

                // Título
                $currentDate = Carbon::now()->format('d/m/Y');
                $sheet->setCellValue('B2', "REPORTE DE VACACIONES - $currentDate");
                $sheet->mergeCells('B2:H2');
                $sheet->getStyle('B2:H2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(40);

                // Encabezados
                $sheet->getStyle('A6:H6')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4F81BD'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Bordes
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle("A6:H{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Ancho automático
                foreach (range('A', 'H') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // === Agregar cálculos al final ===
                $query = Vacacion::query();
                if ($this->request->filled('empleado_id')) {
                    $query->where('empleado_id', $this->request->empleado_id);
                }
                $vacaciones = $query->get();

                $totalVacacionesRestantes = $this->request->filled('empleado_id')
                    ? $vacaciones->first()->empleado->vacaciones_restantes ?? 'N/A'
                    : 'N/A';

                $totalDiasAprobados = $vacaciones->where('estado', 'aprobadas')->sum('duracion_dias');

                $summaryStartRow = $sheet->getHighestRow() + 2;
                $sheet->setCellValue("A{$summaryStartRow}", 'Totales:');
                $sheet->setCellValue("A" . ($summaryStartRow + 1), 'Total de Vacaciones Restantes:');
                $sheet->setCellValue("B" . ($summaryStartRow + 1), $totalVacacionesRestantes);
                $sheet->setCellValue("A" . ($summaryStartRow + 2), 'Total de Vacaciones Aprobadas (Duración en días):');
                $sheet->setCellValue("B" . ($summaryStartRow + 2), $totalDiasAprobados);

                // Estilos para los totales
                $sheet->getStyle("A{$summaryStartRow}:B" . ($summaryStartRow + 2))->applyFromArray([
                    'font' => ['bold' => true],
                ]);
            },
        ];
    }
}
