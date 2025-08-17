<?php

namespace App\Exports;

use App\Models\Vendor;
use App\Models\KategoriVendor;
use App\Models\ProjectPekerjaan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\DB;

class ExportAnnualAreaReport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $vendorFilter;
    protected $yearFilter;

    public function __construct($vendorFilter = null, $yearFilter = null)
    {
        $this->vendorFilter = $vendorFilter;
        $this->yearFilter = $yearFilter;
    }

    public function collection()
    {
        // Get vendors with category 'Blasting & Painting' and sum their amounts
        $blastingPaintingCategory = KategoriVendor::where('name', 'Blasting & Painting')->first();
        
        if (!$blastingPaintingCategory) {
            return collect([]);
        }

        return Vendor::select('vendor.*')
            ->addSelect([
                'total_area' => ProjectPekerjaan::select(DB::raw('SUM(amount)'))
                    ->whereColumn('id_vendor', 'vendor.id'),
                'total_project' => ProjectPekerjaan::select(DB::raw('COUNT(DISTINCT id_project)'))
                    ->whereColumn('id_vendor', 'vendor.id'),
            ])
            ->where('kategori_vendor', $blastingPaintingCategory->id)
            ->when($this->vendorFilter, function ($query) {
                $query->where('id', $this->vendorFilter);
            })
            ->when($this->yearFilter, function ($query) {
                $query->whereHas('projectPekerjaan.projects', function($q) {
                    $q->whereYear('created_at', $this->yearFilter);
                });
            })
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NAMA SUBCONTRACTOR',
            'SUB KATEGORI',
            'TOTAL PROJECT',
            'LUASAN (M)'
        ];
    }

    public function map($vendor): array
    {
        static $no = 0;
        $no++;
        
        return [
            $no,
            $vendor->name,
            $vendor->kategori->name ?? '',
            $vendor->total_project,
            number_format($vendor->total_area, 2, ',', '.') . ' M'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Get the highest row and column
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        
        // Style for header row
        $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Style for data rows
        if ($highestRow > 1) {
            $sheet->getStyle('A2:' . $highestColumn . $highestRow)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]);

            // Center align for No, Total Project columns
            $sheet->getStyle('A2:A' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D2:D' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            // Right align for Luasan column
            $sheet->getStyle('E2:E' . $highestRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        }

        // Set row height
        for ($i = 1; $i <= $highestRow; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(25);
        }

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // No
            'B' => 30,  // Nama Subcontractor
            'C' => 20,  // Sub Kategori
            'D' => 15,  // Total Project
            'E' => 18   // Luasan (M)
        ];
    }
}