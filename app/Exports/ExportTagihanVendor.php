<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportTagihanVendor implements FromView, WithTitle, WithStyles, WithColumnWidths, ShouldAutoSize
{
    protected $request;
    protected $title;
    protected $project;
    public function __construct($request, $title, $project)
    {
        $this->request = $request;
        $this->title = $title;
        $this->project = $project;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A:L' => ['alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                ],
                'font' => [
                            'size' => 10,
                            'name' => 'Times New Roman'
                ]
            ],
            'A1:K2' => ['alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'wrapText' => true,
                ],
                'font' => [
                            'bold' => true,
                            'size' => 12
                        ]
            ],
            'A6:K6' => ['alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'wrapText' => true,
                ],
                'font' => [
                            'bold' => true
                        ]
            ],
            'B' => [ 'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                ],

            ],
            'C:J' => ['alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'wrapText' => true,
                ],
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 7,
            'D' => 5,
            'D' => 6,
            'E' => 6,
            'F' => 5,
            'G' => 5,
            'H' => 5,
            'I' => 7,
            'J' => 5,
            'K' => 15,
            'L' => 15
        ];
    }

    public function view(): View
    {
        $data = groupExportTagihanVendor($this->request, $this->project);
        return view('export.ExportTagihanVendor',['data' => $data]);
    }
}
