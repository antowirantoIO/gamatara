<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OnRequest;
use App\Models\Vendor;
use App\Models\KategoriVendor;
use App\Models\ProjectPekerjaan;
use App\Models\LokasiProject;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exports\ExportSPKSummaryReport;

class SPKSummaryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Get all projects first
            $allProjects = OnRequest::select('id', 'nama_project', 'status')->get();
            
            $data = Vendor::with(['kategori'])
                ->when($request->filled('vendor_id'), function ($query) use ($request) {
                    $query->where('id', $request->vendor_id);
                })
                ->when($request->filled('kategori_id'), function ($query) use ($request) {
                    $query->where('kategori_vendor', $request->kategori_id);
                })
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
                ->get();

            $dataTable = DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_subcontractor', function($row) {
                    return $row->name;
                })
                ->addColumn('sub_kategori', function($row) {
                    return $row->kategori ? $row->kategori->name : '-';
                })
                ->addColumn('on_progress', function($row) {
                    return $row->on_progress_count ?: 0;
                })
                ->addColumn('complete', function($row) {
                    return $row->completed_count ?: 0;
                });
            
            // Add dynamic columns for each project
            foreach ($allProjects as $project) {
                $columnName = 'project_' . $project->id;
                $dataTable->addColumn($columnName, function($row) use ($project) {
                    // Check if this vendor has work on this project
                    $hasProject = ProjectPekerjaan::where('id_vendor', $row->id)
                        ->where('id_project', $project->id)
                        ->exists();
                    
                    if ($hasProject) {
                        if ($project->status == 1) {
                            return '●'; // On Progress
                        } elseif ($project->status == 2) {
                            return '✓'; // Completed
                        }
                    }
                    return '-';
                });
            }
            
            return $dataTable->make(true);
        }
        
        // Get unique vendors and categories for filters
        $vendors = Vendor::orderBy('name')->get();
        $categories = KategoriVendor::orderBy('name')->get();
        $projects = OnRequest::all();
        
        return view('spk_summary.index', compact('vendors', 'categories', 'projects'));
    }

    public function export(Request $request)
    {
        $vendorFilter = $request->get('vendor_filter');
        $categoryFilter = $request->get('category_filter');
        
        $fileName = 'spk_summary_report_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(
            new ExportSPKSummaryReport($vendorFilter, $categoryFilter),
            $fileName
        );
    }
}