<?php

namespace App\Exports;

use App\Models\ProjectPekerjaan;
use App\Models\KategoriVendor;
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
use Carbon\Carbon;

class ExportReplatingReport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $subcontractorFilter;
    protected $projectFilter;

    public function __construct($subcontractorFilter = null, $projectFilter = null)
    {
        $this->subcontractorFilter = $subcontractorFilter;
        $this->projectFilter = $projectFilter;
    }

    public function collection()
    {
        // Get vendors with category 'Replating'
        $replatingCategory = KategoriVendor::where('name', 'Replating')->first();
        
        if (!$replatingCategory) {
            return collect([]);
        }

        // Get project work data for replating vendors with grouping and sum
        $query = ProjectPekerjaan::select(
            'id_vendor',
            'id_project',
            DB::raw('SUM(amount) as total_amount')
        )
        ->with([
            'vendors',
            'projects',
            'lokasi'
        ])
        ->whereHas('vendors', function($query) use ($replatingCategory) {
            $query->where('kategori_vendor', $replatingCategory->id);
        })
        ->when($this->subcontractorFilter, function($query) {
            $query->whereHas('vendors', function($q) {
                $q->where('name', 'like', '%' . $this->subcontractorFilter . '%');
            });
        })
        ->when($this->projectFilter, function($query) {
            $query->whereHas('projects', function($q) {
                $q->where('nama_project', 'like', '%' . $this->projectFilter . '%');
            });
        })
        ->groupBy('id_vendor', 'id_project');
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'NAMA SUBCONTRACTOR',
            'NAMA PROJECT',
            'TONASE (KG)',
            'DURASI PROJECT (DAY)',
            'ON PROGRESS',
            'COMPLETE'
        ];
    }

    public function map($row): array
    {
        // Calculate duration
        $duration = '-';
        if ($row->projects && $row->projects->created_at && $row->projects->target_selesai) {
            $startDate = Carbon::parse($row->projects->created_at);
            $endDate = Carbon::parse($row->projects->target_selesai);
            $duration = $startDate->diffInDays($endDate);
        } elseif ($row->projects && $row->projects->created_at) {
            $startDate = Carbon::parse($row->projects->created_at);
            $currentDate = Carbon::now();
            $duration = $startDate->diffInDays($currentDate);
        }

        // Check status
        $onProgress = ($row->projects && $row->projects->status == 1) ? '✓' : '-';
        $complete = ($row->projects && $row->projects->status == 2) ? '✓' : '-';

        return [
            $row->vendors ? $row->vendors->name : '-',
            $row->projects ? $row->projects->nama_project : '-',
            $row->total_amount ? number_format($row->total_amount, 2) : '0',
            $duration,
            $onProgress,
            $complete
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
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
            ],
            // Data rows styling
            'A2:F1000' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
            // Center align for specific columns
            'C:F' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25, // NAMA SUBCONTRACTOR
            'B' => 20, // NAMA PROJECT
            'C' => 15, // TONASE (KG)
            'D' => 20, // DURASI PROJECT (DAY)
            'E' => 12, // ON PROGRESS
            'F' => 12, // COMPLETE
        ];
    }
}