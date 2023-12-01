<?php

namespace App\Exports;

use App\Models\Fleet\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportReportProjectLocation implements FromView
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
        
        return view('export.ExportReportProjectLocation', ['data' => $this->data]);
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            '1' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
            'A:D' => ['alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'size' => 12,
                    'name' => 'Times New Roman'
                ]
            ],
            'A2:D2' => ['alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'size' => 14,
                    'bold' => true,

                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5
        ];
    }
}
