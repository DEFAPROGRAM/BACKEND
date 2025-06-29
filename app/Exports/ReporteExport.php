<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReporteExport implements FromCollection, WithHeadings
{
    private $datos;
    private $headers;

    public function __construct($datos)
    {
        $this->datos = $datos;
        $this->headers = $this->getHeaders();
    }

    public function collection()
    {
        return collect($this->datos);
    }

    public function headings(): array
    {
        return $this->headers;
    }

    private function getHeaders()
    {
        if (empty($this->datos)) {
            return [];
        }

        $firstRow = $this->datos[0];
        return array_keys($firstRow);
    }
}
