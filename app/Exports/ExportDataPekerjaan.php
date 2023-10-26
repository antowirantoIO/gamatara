<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithBackgroundColor;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportDataPekerjaan implements FromView,ShouldAutoSize,WithStyles,WithColumnWidths,WithDrawings, WithEvents
{

    protected $data ;
    protected $project ;
    public function __construct($data,$project)
    {
        $this->data = $data;
        $this->project = $project;
    }

    public function drawings()
    {
        // Membuat objek gambar
        $drawing = new Drawing();
        $drawing->setName('YourImage.png'); // Nama gambar
        $drawing->setDescription('Description');
        $drawing->setPath(public_path('assets/images/logo.png')); // Path ke gambar
        $drawing->setHeight(100); // Tinggi gambar
        $drawing->setCoordinates('B2'); // Sel tempat gambar akan disisipkan
        $drawing->setOffsetX(45); // Posisi horizontal
        $drawing->setOffsetY(5); // Posisi vertikal

        return [
            $drawing,
        ];
    }


    public function view(): View
    {
        return view('export.ExportPekerjaanOnProgress',['data'=> $this->data,'project' => $this->project]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'A9:K9' => ['alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'wrapText' => true,
                ],
                'font' => ['bold' => true]
            ],
            'c' =>  ['alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'wrapText' => true,
            ]],
            '6' =>  ['alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]],
            'A:K' => ['height' => 30],
            'A8:A1000' => [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ]
            ],
            'A:K'  => [
                'font' => ['size' => 8],
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ]
            ],
            'A2:J5' => [
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['argb' => '000000'], // Warna border (hitam)
                    ],
                ],
            ],
            'A6:J6'=> [
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['argb' => '000000'], // Warna border (hitam)
                    ],
                ],
            ],
            'A7:D7'=> [
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['argb' => '000000'], // Warna border (hitam)
                    ],
                ],
            ],
            'A7:K7' => [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ]
            ],
            'A8:J8' => [
                'alignment' => [
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'font' => ['bold' => true],
                'borders' => [
                    'inside' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['argb' => '000000'], // Warna border (hitam)
                    ],
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['argb' => '000000'], // Warna border (hitam)
                    ]
                ],
            ],

            'A9:J9'=> [
                'borders' => [
                    'inside' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['argb' => '000000'], // Warna border (hitam)
                    ],
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['argb' => '000000'], // Warna border (hitam)
                    ],
                    'vertical' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['argb' => '000000'], // Warna border (hitam)
                    ],
                    'horizontal' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['argb' => '000000'], // Warna border (hitam)
                    ],
                ],
            ],

            'C2' => ['font' => ['bold' => true]],
            'C2:J5' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                ]
            ],

            'C2:C5' =>  ['alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]],
            'E7:J7'=> [
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                        'color' => ['argb' => '000000'], // Warna border (hitam)
                    ],
                ],
            ],
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
            'K' => 5
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {

                $event->sheet->getDelegate()->getStyle('A7:D7')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('95B3D7');
                $event->sheet->getDelegate()->getStyle('A9:J9')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('95B3D7');

            },
        ];
    }


}
