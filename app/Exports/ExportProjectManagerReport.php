<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportProjectManagerReport implements FromView, WithStyles
{
    protected $data;
    protected $projectManagers;

    public function __construct($data, $projectManagers)
    {
        $this->data = $data;
        $this->projectManagers = $projectManagers;
    }

    public function view(): View
    {
        return view('export.ExportProjectManagerReport', [
            'data' => $this->data,
            'projectManagers' => $this->projectManagers
        ]);
    }
    
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('1')->getFont()->setBold(true);
        $sheet->getStyle('1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:Z1')->getFill()
        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFC3CFEA');

        return $sheet;
    }
}