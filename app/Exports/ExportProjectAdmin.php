<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportProjectAdmin implements FromView, WithStyles
{
    protected $data;
    protected $projectAdmins;

    public function __construct($data, $projectAdmins)
    {
        $this->data = $data;
        $this->projectAdmins = $projectAdmins;
    }

    public function view(): View
    {
        return view('export.ExportProjectAdmin', [
            'data' => $this->data,
            'projectAdmins' => $this->projectAdmins
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