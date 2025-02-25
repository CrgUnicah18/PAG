<?php
namespace App\Exports;

use App\Models\Empleado;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmpleadosExport implements FromCollection, WithHeadings
{
    // Recupera todos los empleados de la base de datos
    public function collection()
    {
        return Empleado::all(['nombre', 'apellido', 'telefono', 'grupo_id', 'oficina_id', 'fecha_ingreso', 'tipo_contrato', 'estado']);
    }

    // Encabezados de las columnas
    public function headings(): array
    {
        return [
            'Nombre',
            'Apellido',
            'Teléfono',
            'Grupo',
            'Oficina',
            'Fecha de ingreso',
            'Tipo de contrato',
            'Estado'
        ];
    }
}
