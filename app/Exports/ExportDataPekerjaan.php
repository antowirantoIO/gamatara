<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportDataPekerjaan implements FromView,ShouldAutoSize,WithColumnWidths,WithStyles
{

    protected $data ;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('export.ExportPekerjaanOnProgress',['data'=> $this->data]);
    }

    public function styles(Worksheet $sheet)
    {
        return [

        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 50,
            'C' => 5,
            'D' => 5,
            'E' => 5,
            'F' => 5,
            'G' => 5,
            'H' => 5,
            'I' => 5,
            'J' => 5,
            'K' => 15
        ];
    }
}
