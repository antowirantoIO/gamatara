<?php

namespace App\Http\Controllers\Report;

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
use App\Exports\ExportReplatingReport;

class ReplatingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Get vendors with category 'Replating'
            $replatingCategory = KategoriVendor::where('name', 'Replating')->first();
            
            if (!$replatingCategory) {
                return DataTables::of(collect([]))->make(true);
            }
            
            // Get project work data for replating vendors
            $data = ProjectPekerjaan::with([
                'vendors',
                'projects',
                'lokasi'
            ])
            ->whereHas('vendors', function($query) use ($replatingCategory) {
                $query->where('kategori_vendor', $replatingCategory->id);
            })
            ->when($request->filled('subcontractor_filter'), function($query) use ($request) {
                $query->whereHas('vendors', function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->subcontractor_filter . '%');
                });
            })
            ->when($request->filled('project_filter'), function($query) use ($request) {
                $query->whereHas('projects', function($q) use ($request) {
                    $q->where('nama_project', 'like', '%' . $request->project_filter . '%');
                });
            })
            ->select('id_vendor', 'id_project', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('id_vendor', 'id_project')
            ->get();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_subcontractor', function($row) {
                    return $row->vendors ? $row->vendors->name : '-';
                })
                ->addColumn('nama_project', function($row) {
                    return $row->projects ? $row->projects->nama_project : '-';
                })
                ->addColumn('tonase_kg', function($row) {
                    return $row->total_amount ? number_format($row->total_amount, 2) : '0';
                })
                ->addColumn('durasi_project_day', function($row) {
                    if ($row->projects && $row->projects->created_at && $row->projects->target_selesai) {
                        $startDate = Carbon::parse($row->projects->created_at);
                        $endDate = Carbon::parse($row->projects->target_selesai);
                        return $startDate->diffInDays($endDate);
                    } elseif ($row->projects && $row->projects->created_at) {
                        $startDate = Carbon::parse($row->projects->created_at);
                        $currentDate = Carbon::now();
                        return $startDate->diffInDays($currentDate);
                    }
                    return '-';
                })
                ->addColumn('durasi_project_on_progress', function($row) {
                    if ($row->projects && $row->projects->status == 1) {
                        return '✓';
                    }
                    return '';
                })
                ->addColumn('status_project', function($row) {
                    if ($row->projects && $row->projects->status == 2) {
                        return '✓';
                    }
                    return '';
                })
                ->rawColumns(['durasi_project_on_progress', 'status_project'])
                ->make(true);
        }
        
        // Get unique subcontractors for filter
        $replatingCategory = KategoriVendor::where('name', 'Replating')->first();
        $subcontractors = collect([]);
        $projects = collect([]);
        
        if ($replatingCategory) {
            $subcontractors = Vendor::where('kategori_vendor', $replatingCategory->id)
                ->orderBy('name')
                ->get();
                
            $projects = OnRequest::whereHas('progress.vendors', function($query) use ($replatingCategory) {
                $query->where('kategori_vendor', $replatingCategory->id);
            })
            ->orderBy('nama_project')
            ->get();
        }
        
        return view('replating_report.index', compact('subcontractors', 'projects'));
    }
    
    public function export(Request $request)
    {
        $subcontractorFilter = $request->get('subcontractor_filter');
        $projectFilter = $request->get('project_filter');
        
        $fileName = 'replating_report_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(
            new ExportReplatingReport($subcontractorFilter, $projectFilter),
            $fileName
        );
    }
}