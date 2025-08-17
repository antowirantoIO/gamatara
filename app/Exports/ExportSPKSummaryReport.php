<?php

namespace App\Exports;

use App\Models\Vendor;
use App\Models\KategoriVendor;
use App\Models\ProjectPekerjaan;
use App\Models\OnRequest;
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

class ExportSPKSummaryReport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $vendorFilter;
    protected $categoryFilter;
    protected $projects;

    public function __construct($vendorFilter = null, $categoryFilter = null)
    {
        $this->vendorFilter = $vendorFilter;
        $this->categoryFilter = $categoryFilter;
        $this->projects = OnRequest::all();
    }

    public function collection()
    {
        // Get all vendors with their categories and project counts
        $data = Vendor::with(['kategori'])
            ->select('vendor.*')
            ->addSelect([
                'on_progress_count' => ProjectPekerjaan::select(DB::raw('COUNT(DISTINCT id_project)'))
                    ->whereColumn('id_vendor', 'vendor.id')
                    ->whereHas('projects', function($query) {
                        $query->where('status', 1); // On Progress
                    }),
                'completed_count' => ProjectPekerjaan::select(DB::raw('COUNT(DISTINCT id_project)'))
                    ->whereColumn('id_vendor', 'vendor.id')
                    ->whereHas('projects', function($query) {
                        $query->where('status', 2); // Completed
                    })
            ])
            ->when($this->vendorFilter, function($query) {
                $query->where('name', 'like', '%' . $this->vendorFilter . '%');
            })
            ->when($this->categoryFilter, function($query) {
                $query->whereHas('kategori', function($q) {
                    $q->where('name', 'like', '%' . $this->categoryFilter . '%');
                });
            })
            ->get();

        return $data;
    }

    public function headings(): array
    {
        $headings = [
            'No',
            'Nama Subcontractor',
            'Sub Kategori',
            'On Progress',
            'Complete'
        ];
        
        // Add dynamic project columns
        foreach ($this->projects as $project) {
            $headings[] = $project->nama_project;
        }
        
        return $headings;
    }

    public function map($vendor): array
    {
        static $counter = 0;
        $counter++;
        
        $row = [
            $counter,
            $vendor->name,
            $vendor->kategori ? $vendor->kategori->name : '-',
            $vendor->on_progress_count ?? 0,
            $vendor->completed_count ?? 0
        ];
        
        // Add dynamic project status columns
        foreach ($this->projects as $project) {
            $projectStatus = '';
            
            // Check if vendor has work on this project
            $hasProject = ProjectPekerjaan::where('id_vendor', $vendor->id)
                ->where('id_project', $project->id)
                ->exists();
                
            if ($hasProject) {
                if ($project->status == 1) {
                    $projectStatus = '●'; // On Progress
                } elseif ($project->status == 2) {
                    $projectStatus = '✓'; // Completed
                }
            }
            
            $row[] = $projectStatus;
        }
        
        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        // Calculate the last column based on number of projects
        $totalColumns = 5 + count($this->projects); // 5 base columns + project columns
        $lastColumn = chr(64 + $totalColumns); // Convert to letter (A=65, so 64+1=A)
        
        $styles = [
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
            "A2:{$lastColumn}1000" => [
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
            'A:A' => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]
        ];
        
        // Add center alignment for On Progress, Complete and all project columns (D onwards)
        if ($totalColumns >= 4) {
            $styles["D:{$lastColumn}"] = [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ];
        }
        
        return $styles;
    }

    public function columnWidths(): array
    {
        $widths = [
            'A' => 10,
            'B' => 30,
            'C' => 20,
            'D' => 15,
            'E' => 15,
        ];
        
        // Add dynamic widths for project columns
        $columnIndex = 'F';
        foreach ($this->projects as $project) {
            $widths[$columnIndex] = 30;
            $columnIndex++;
        }
        
        return $widths;
    }
}