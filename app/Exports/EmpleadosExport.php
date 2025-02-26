<?php
namespace App\Exports;

use App\Models\Empleado;
use Maatwebsite\Excel\Concerns\FromCollection;

class EmpleadosExport implements FromCollection
{
    protected $empleados;

    public function __construct($empleados)
    {
        $this->empleados = $empleados;
    }

    public function collection()
    {
        return $this->empleados;
    }
}
