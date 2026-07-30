<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {

        return [
            'ID Municipio',
            'Nombre Municipio',
            'Inicio Semana',
            'Fin Semana',
            'Total Licencias'
        ];
    }
}
