<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\KategoriVendor;
use App\Models\ProjectPekerjaan;
use App\Exports\ExportAnnualAreaReport;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnnualAreaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Get vendors with category 'Blasting & Painting' and sum their amounts
            $blastingPaintingCategory = KategoriVendor::where('name', 'Blasting & Painting')->first();
            
            if (!$blastingPaintingCategory) {
                return DataTables::of(collect([]))->make(true);
            }

            $data = Vendor::select('vendor.*')
                ->addSelect([
                    'total_area' => ProjectPekerjaan::select(DB::raw('SUM(amount)'))
                        ->whereColumn('id_vendor', 'vendor.id'),
                    'total_project' => ProjectPekerjaan::select(DB::raw('COUNT(DISTINCT id_project)'))
                        ->whereColumn('id_vendor', 'vendor.id'),
                ])
                ->where('kategori_vendor', $blastingPaintingCategory->id)
                ->when($request->filled('vendor_filter'), function ($query) use ($request) {
                    $query->where('id', $request->vendor_filter);
                })
                ->when($request->filled('year_filter'), function ($query) use ($request) {
                    $query->whereHas('projectPekerjaan.projects', function($q) use ($request) {
                        $q->whereYear('created_at', $request->year_filter);
                    });
                })
                // ->having('total_area', '>', 0)
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_subcontractor', function($row) {
                    return $row->name;
                })
                ->addColumn('sub_kategori', function($row) {
                    return $row->kategori->name;
                })
                ->addColumn('total_area', function($row) {
                    return number_format($row->total_area, 2, ',', '.') . ' M';
                })
                ->addColumn('total_project', function($row) {
                    return $row->total_project;
                })
                ->addColumn('total_area_raw', function($row) {
                    return $row->total_area;
                })
                ->make(true);
        }

        // Get all blasting & painting vendors for filter
        $blastingPaintingCategory = KategoriVendor::where('name', 'Blasting & Painting')->first();
        $vendors = collect([]);
        
        if ($blastingPaintingCategory) {
            $vendors = Vendor::where('kategori_vendor', $blastingPaintingCategory->id)
                ->get();
        }

        // Get available years from projects
        $years = ProjectPekerjaan::join('project', 'project_pekerjaan.id_project', '=', 'project.id')
            ->whereHas('vendors', function($query) use ($blastingPaintingCategory) {
                if ($blastingPaintingCategory) {
                    $query->where('kategori_vendor', $blastingPaintingCategory->id);
                }
            })
            ->selectRaw('YEAR(project.created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('annual_area.index', compact('vendors', 'years'));
    }

    public function export(Request $request)
    {
        $vendorFilter = $request->vendor_filter;
        $yearFilter = $request->year_filter;
        
        return Excel::download(
            new ExportAnnualAreaReport($vendorFilter, $yearFilter), 
            'Annual_Area_Report_' . Carbon::now()->format('Y-m-d') . '.xlsx'
        );
    }
}