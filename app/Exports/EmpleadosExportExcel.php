<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Font;

class EmpleadosExportExcel implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $empleados;
    protected $camposSeleccionados;

    public function __construct($empleados, $camposSeleccionados)
    {
        $this->empleados = $empleados;
        $this->camposSeleccionados = $camposSeleccionados;
    }

    public function collection()
    {
        return $this->empleados;
    }

    public function map($empleado): array
    {
        $fila = [];

        foreach ($this->camposSeleccionados as $campo) {
            switch ($campo) {
                case 'oficina':
                    $fila[] = $empleado->oficina->nombre ?? '';
                    break;
                case 'grupo':
                    $fila[] = $empleado->grupo->nombre ?? '';
                    break;
                case 'rol':
                    $fila[] = isset($empleado->roles) ? implode(', ', $empleado->roles) : '';
                    break;
                default:
                    $fila[] = $empleado->$campo ?? '';
                    break;
            }
        }

        return $fila;
    }

    public function headings(): array
    {
        $nombres = [
            'dn' => 'DNI',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'genero' => 'Género',
            'direccion' => 'Dirección',
            'telefono' => 'Teléfono',
            'fecha_nacimiento' => 'Fecha de Nacimiento',
            'fecha_ingreso' => 'Fecha de Ingreso',
            'oficina' => 'Oficina',
            'grupo' => 'Grupo',
            'tipo_contrato' => 'Tipo de Contrato',
            'rol' => 'Rol',
            'email' => 'Email',
            'cargo' => 'Cargo',
            'vacaciones_tomadas' => 'Vacaciones Tomadas',
            'vacaciones_restantes' => 'Vacaciones Restantes',
        ];

        return array_map(function ($campo) use ($nombres) {
            return $nombres[$campo] ?? $campo;
        }, $this->camposSeleccionados);
    }

    public function styles($sheet)
    {
        // Limpiar las filas anteriores (evitar duplicados)
        $sheet->removeRow(1, 7); // Remover las filas 1 a 7 donde no queremos datos

        // Título "Proyecto Aldea Global" en la celda E3
        $sheet->mergeCells('E3:K3');
        $sheet->setCellValue('E3', 'Proyecto Aldea Global');
        $sheet->getStyle('E3')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Subtítulo "Ciudad de Siguatepeque. Reporte de Empleados" en la celda E4
        $sheet->mergeCells('E4:K4');
        $sheet->setCellValue('E4', 'Ciudad de Siguatepeque. Reporte de Empleados');
        $sheet->getStyle('E4')->getFont()->setSize(14);
        $sheet->getStyle('E4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Título en la fila E3 (ya lo hemos agregado)
        $sheet->mergeCells('E5:K5');
        $sheet->setCellValue('E5', 'Reporte de Empleados de Proyecto Aldea Global');
        $sheet->getStyle('E5')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('E5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Estilo para la cabecera de las columnas
        $columnaFinal = chr(64 + count($this->camposSeleccionados) + 3); // Calculamos la última columna dependiendo de las columnas seleccionadas

        // Aseguramos que el rango de las cabeceras se cubra dinámicamente
        $sheet->getStyle('D8:' . $columnaFinal . '8')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'], // Color blanco
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4CAF50'], // Color verde
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Imagen en columna D con padding de 4
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo de Aldea Global');
        $drawing->setPath(public_path('images/logopag2.png'));
        $drawing->setHeight(70); // Tamaño del logo
        $drawing->setCoordinates('D3'); // Colocar en la columna D
        $drawing->setOffsetX(30); // Padding horizontal de 30
        $drawing->setOffsetY(4); // Padding vertical de 4
        $drawing->setWorksheet($sheet);

        // Ajustar las columnas seleccionadas para que encajen con el contenido
        foreach (range('D', $columnaFinal) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true); // Ajustar solo las columnas seleccionadas
        }

        // Insertar los títulos de las columnas en la fila 8
        $col = 'D'; // Empezamos desde la columna D
        foreach ($this->camposSeleccionados as $campo) {
            $sheet->setCellValue($col . '8', $this->getColumnHeading($campo)); // Título de columna
            $col++; // Mover a la siguiente columna
        }

        // Insertar los datos de los empleados en la tabla (a partir de la fila 9)
        $row = 9; // Comenzamos desde la fila 9 para los datos (después de las cabeceras)
        foreach ($this->empleados as $empleado) {
            $col = 'D'; // Empezamos desde la columna D
            foreach ($this->camposSeleccionados as $campo) {
                switch ($campo) {
                    case 'oficina':
                        $sheet->setCellValue($col . $row, $empleado->oficina->nombre ?? '');
                        break;
                    case 'grupo':
                        $sheet->setCellValue($col . $row, $empleado->grupo->nombre ?? '');
                        break;
                    case 'rol':
                        $sheet->setCellValue($col . $row, isset($empleado->roles) ? implode(', ', $empleado->roles) : '');
                        break;
                    default:
                        $sheet->setCellValue($col . $row, $empleado->$campo ?? '');
                        break;
                }
                $col++; // Mover a la siguiente columna
            }
            $row++; // Mover a la siguiente fila
        }

        // Estilo para las filas de datos
        foreach (range(9, 8 + count($this->empleados)) as $row) {
            $sheet->getStyle('D' . $row . ':' . $columnaFinal . $row)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);
        }

        return [
            'D1' => ['font' => ['bold' => true, 'size' => 16]], // Título
        ];
    }


    public function title(): string
    {
        return 'Reporte de Empleados';
    }

    // Función para obtener el encabezado de cada columna
    private function getColumnHeading($campo)
    {
        $nombres = [
            'dn' => 'DNI',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'genero' => 'Género',
            'direccion' => 'Dirección',
            'telefono' => 'Teléfono',
            'fecha_nacimiento' => 'Fecha de Nacimiento',
            'fecha_ingreso' => 'Fecha de Ingreso',
            'oficina' => 'Oficina',
            'grupo' => 'Grupo',
            'tipo_contrato' => 'Tipo de Contrato',
            'rol' => 'Rol',
            'email' => 'Email',
            'cargo' => 'Cargo',
            'vacaciones_tomadas' => 'Vacaciones Tomadas',
            'vacaciones_restantes' => 'Vacaciones Restantes',
        ];

        return $nombres[$campo] ?? $campo;
    }
}
