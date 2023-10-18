<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportPekerjaanVendor implements FromView,WithStyles,ShouldAutoSize, WithColumnWidths
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $nama_project;
    protected $nama_vendor;
    protected $data;

    public function __construct($nama_project,$nama_vendor, $data)
    {
        $this->nama_project = $nama_project;
        $this->nama_vendor = $nama_vendor;
        $this->data = $data;
    }

    public function view(): View
    {
        return view('export.ExportPekerjaanVendor',[
            'data' => $this->data,
            'nama_project' => $this->nama_project,
            'nama_vendor' => $this->nama_vendor
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'font' => [
                    'size' => 20,
                ],
            ],
            2 => [
                'font' => [
                    'bold' => true,
                ],
            ],
            'A:I' => ['alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ]]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20
        ];
    }
}
