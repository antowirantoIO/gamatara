<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\KategoriVendor;
use App\Models\ProjectPekerjaan;
use App\Exports\ExportAnnualTonnageReport;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnnualTonnageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Get vendors with category 'Replating' and sum their amounts
            $replatingCategory = KategoriVendor::where('name', 'Replating')->first();
            
            if (!$replatingCategory) {
                return DataTables::of(collect([]))->make(true);
            }

            $data = Vendor::select('vendor.*')
                ->addSelect([
                    'total_tonnage' => ProjectPekerjaan::select(DB::raw('SUM(amount)'))
                        ->whereColumn('id_vendor', 'vendor.id'),
                    'total_project' => ProjectPekerjaan::select(DB::raw('COUNT(DISTINCT id_project)'))
                        ->whereColumn('id_vendor', 'vendor.id'),
                ])
                ->where('kategori_vendor', $replatingCategory->id)
                ->when($request->filled('vendor_filter'), function ($query) use ($request) {
                    $query->where('id', $request->vendor_filter);
                })
                ->when($request->filled('year_filter'), function ($query) use ($request) {
                    $query->whereHas('projectPekerjaan', function($q) use ($request) {
                        $q->whereHas('projects', function($subQ) use ($request) {
                            $subQ->whereYear('created_at', $request->year_filter);
                        });
                    });
                })
                // ->having('total_tonnage', '>', 0)
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_subcontractor', function($row) {
                    return $row->name;
                })
                ->addColumn('sub_kategori', function($row) {
                    return $row->kategori->name;
                })
                ->addColumn('total_tonnage', function($row) {
                    return number_format($row->total_tonnage, 2, ',', '.') . ' KG';
                })
                ->addColumn('total_project', function($row) {
                    return $row->total_project;
                })
                ->addColumn('total_tonnage_raw', function($row) {
                    return $row->total_tonnage;
                })
                ->make(true);
        }

        // Get all replating vendors for filter
        $replatingCategory = KategoriVendor::where('name', 'Replating')->first();
        $vendors = collect([]);
        
        if ($replatingCategory) {
            $vendors = Vendor::where('kategori_vendor', $replatingCategory->id)
                ->get();
        }

        // Get available years from projects
        $years = ProjectPekerjaan::join('project', 'project_pekerjaan.id_project', '=', 'project.id')
            ->whereHas('vendors', function($query) use ($replatingCategory) {
                if ($replatingCategory) {
                    $query->where('kategori_vendor', $replatingCategory->id);
                }
            })
            ->selectRaw('YEAR(project.created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('annual_tonnage.index', compact('vendors', 'years'));
    }

    public function export(Request $request)
    {
        $vendorFilter = $request->vendor_id;
        $yearFilter = $request->year;
        
        return Excel::download(
            new ExportAnnualTonnageReport($vendorFilter, $yearFilter), 
            'Annual_Tonnage_Report_' . Carbon::now()->format('Y-m-d') . '.xlsx'
        );
    }
}