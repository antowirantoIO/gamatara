<?php

namespace App\Exports;

use App\Models\OnRequest;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportTagihanVendor implements FromView, WithTitle, WithStyles, WithColumnWidths, ShouldAutoSize, WithEvents
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $event->sheet->getDelegate()->getPageSetup()->setPaperSize(14.00,8.50);
                // $event->sheet->getDelegate()->getPageSetup()->setFitToWidth(14.00);
                // $event->sheet->getDelegate()->getPageSetup()->setFitToHeight(8.50);

                // Set area cetak
                $event->sheet->getDelegate()->getPageMargins()->setTop(0);
                $event->sheet->getDelegate()->getPageMargins()->setRight(0.39);
                $event->sheet->getDelegate()->getPageMargins()->setLeft(0.39);
                $event->sheet->getDelegate()->getPageMargins()->setBottom(0);

                // Atur lebar dan tinggi kertas
                $event->sheet->getDelegate()->getPageSetup()->setHorizontalCentered(true);
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_LEGAL_EXTRA_PAPER);

        return [
            'A:L' => ['alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
                ],
                'font' => [
                            'size' => 12,
                            'name' => 'Times New Roman'
                ]
            ],
            'A4' => ['alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ],
                'font' => [
                    'bold' => true,
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
                'wrapText' => true,
                ],
            ],
            'C6:J6' => ['alignment' => [
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
            'B' => 30,
            'C' => 7,
            'D' => 5,
            'D' => 6,
            'E' => 6,
            'F' => 7,
            'G' => 5,
            'H' => 5,
            'I' => 7,
            'J' => 5,
            'K' => 15,
            'L' => 20
        ];
    }

    public function view(): View
    {
        $data = groupExportTagihanVendor($this->request, $this->project);
        $name = OnRequest::where('id',$this->request->id_project)->first();
        return view('export.ExportTagihanVendor',['data' => $data,'name' => $name,'title' => $this->title]);
    }
}
