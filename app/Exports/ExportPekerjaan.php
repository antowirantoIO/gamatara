<?php

namespace App\Exports;

use App\Models\Fleet\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportPekerjaan implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        
        return view('export.ExportPekerjaan', ['data' => $this->data]);
    }
    
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:AB1')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFC3CFEA');

        return $sheet;
    }
}
