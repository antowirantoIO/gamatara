<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ExportLaporanVendor;
use App\Models\Vendor;
use App\Models\ProjectPekerjaan;
use DB;

class LaporanVendorController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Vendor::filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('jumlah_project', function ($vendor) {
                if($vendor->projectPekerjaan)
                {
                    return $vendor->projectPekerjaan->count();
                }else{
                    return "0";
                }
            })
            ->addColumn('nilai_project', function ($vendor) {
                $totalHargaCustomer = 0;
            
                if ($vendor->projectPekerjaan) {
                    foreach($vendor->projectPekerjaan as $value){
            
                        if ($value) {
                            $totalHargaCustomer += $value->harga_vendor ?? '' * $value->qty ?? '';
                        }
                        
                    }
                }
            
                return 'Rp '. number_format($totalHargaCustomer, 0, ',', '.');
            })
            ->addColumn('action', function($data){
                return '<a href="'.route('laporan_vendor.detail', $data->id).'" class="btn btn-warning btn-sm">
                    <span>
                        <i><img src="'.asset('assets/images/eye.svg').'" style="width: 15px;"></i>
                    </span>
                </a>';
            })
            ->rawColumns(['action'])
            ->make(true);                    
        }

        $tahun = now()->format('Y');

        return view('laporan_vendor.index',compact('tahun'));
    }

    public function chart(Request $request)
    {
        // $data = OnRequest::select('pm_id', 'status')->with(['pm','pm.karyawan'])->get();

        $tahun = now()->format('Y');

        $byTonase = Vendor::join('project_pekerjaan as B', 'vendor.id', '=', 'B.id_vendor')
                        ->join('project as C', function ($join) use ($tahun) {
                            $join->on('B.id_project', '=', 'C.id')
                                ->whereYear('C.created_at', '=', $tahun);
                        })
                        ->select('vendor.id', 'vendor.name', DB::raw('SUM(B.amount) as tonase'))
                        ->groupBy('vendor.id', 'vendor.name')
                        ->orderByDesc(DB::raw('SUM(B.amount)'))
                        ->get();

            $byVolume = Vendor::join('project_pekerjaan as B', 'vendor.id', '=', 'B.id_vendor')
                        ->join('project as C', function ($join) use ($tahun) {
                            $join->on('B.id_project', '=', 'C.id')
                                ->whereRaw('YEAR(C.created_at) = ?', [$tahun]);
                        })
                        ->select('vendor.id', 'vendor.name', \DB::raw('SUM(B.amount) as volume'))
                        ->groupBy('vendor.id', 'vendor.name')
                        ->orderByDesc(\DB::raw('SUM(B.amount)'))
                        ->get();

        // $chartData = $byTonase->groupBy('pm_id')->map(function (&$groupedData) {
        //     $onProgressCount = $groupedData->where('status', 1)->count();
        //     $completeCount = $groupedData->where('status', 2)->count();

        //     $employeeName = $groupedData->first()->pm->karyawan->name;

        //     return [
        //         'Employee' => $employeeName,
        //         'On Progress' => $onProgressCount,
        //         'Complete' => $completeCount,
        //     ];

        // });

        return response()->json($byTonase);
    }
}
