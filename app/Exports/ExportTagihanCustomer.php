<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportTagihanCustomer implements FromView, WithStyles,WithColumnWidths,WithEvents,ShouldAutoSize, WithDrawings
{
    protected $data ;
    protected $name;

    public function __construct($data,$name)
    {
        $this->data = $data;
        $this->name = $name;
    }

    public function drawings()
    {
         // Membuat objek gambar
         $drawing = new Drawing();
         $drawing->setName('YourImage.png'); // Nama gambar
         $drawing->setDescription('Description');
         $drawing->setPath(public_path('assets/images/logo.png')); // Path ke gambar
         $drawing->setHeight(130); // Tinggi gambar
         $drawing->setCoordinates('D1'); // Sel tempat gambar akan disisipkan
         $drawing->setOffsetX(55); // Posisi horizontal
         $drawing->setOffsetY(5); // Posisi vertikal

         return [
             $drawing,
         ];
    }

    public function view(): View
    {
        return view('export.ExportTagihanCustomer',['data' => $this->data,'name' => $this->name]);
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
                            'size' => 10,
                            'name' => 'Times New Roman'
                ]
            ],
            'A2:L7' => ['alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'wrapText' => true,
                ],
                'font' => [
                    'size' => 12,
                    'name' => 'Times New Roman'
                ]
            ],
            'A2:L7' => ['alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'wrapText' => true,
                ],
                'font' => [
                    'size' => 16,
                    'name' => 'Times New Roman'
                ]
            ],
            'A3:L5' => ['alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'wrapText' => true,
                ],
                'font' => [
                    'size' => 10,
                    'name' => 'Times New Roman'
                ]
            ],
            'C:J' => ['alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'wrapText' => true,
                ]
            ],
            'A9:L9' => ['alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'wrapText' => true,
                ],
                'font' => [
                    'name' => 'Times New Roman',
                    'bold' => true
                ]
            ],
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
            'F' => 6,
            'G' => 5,
            'H' => 5,
            'I' => 7,
            'J' => 5,
            'K' => 15,
            'L' => 15
        ];
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
}
