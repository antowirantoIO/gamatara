<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportProjectEngineerReport implements FromView, WithStyles
{
    protected $data;
    protected $projectEngineers;

    public function __construct($data, $projectEngineers)
    {
        $this->data = $data;
        $this->projectEngineers = $projectEngineers;
    }

    public function view(): View
    {
        return view('export.ExportProjectEngineerReport', [
            'data' => $this->data,
            'projectEngineers' => $this->projectEngineers
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