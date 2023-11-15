<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExportReportVendorDetail;
use App\Exports\ExportReportVendor;
use App\Models\Vendor;
use App\Models\ProjectPekerjaan;
use App\Models\OnRequest;
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
            ->addColumn('nilai_tagihan', function ($vendor) {
                $totalHargaCustomer = 0;
            
                if ($vendor->projectPekerjaan) {
                    foreach($vendor->projectPekerjaan as $value){
            
                        if ($value) {
                            $totalHargaCustomer += $value->harga_vendor * $value->qty;
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

        if($request->by != null){
            $by = $request->by;
        }else{
            $by = "Tonase";
        }

        if($by == 'Tonase' )
        {
            $result = Vendor::join('project_pekerjaan as B', 'vendor.id', '=', 'B.id_vendor')
                        ->join('project as C', function ($join) use ($tahun) {
                            $join->on('B.id_project', '=', 'C.id')
                                ->whereYear('C.created_at', '=', $tahun);
                        })
                        ->select('vendor.id', 'vendor.name', DB::raw('SUM(B.amount) as tonase'))
                        ->groupBy('vendor.id', 'vendor.name')
                        ->orderByDesc(DB::raw('SUM(B.amount)'))
                        ->get();
        }else{
            $result = Vendor::join('project_pekerjaan as B', 'vendor.id', '=', 'B.id_vendor')
                    ->join('project as C', function ($join) use ($tahun) {
                        $join->on('B.id_project', '=', 'C.id')
                            ->whereRaw('YEAR(C.created_at) = ?', [$tahun]);
                    })
                    ->select('vendor.id', 'vendor.name', \DB::raw('SUM(B.amount) as tonase'))
                    ->groupBy('vendor.id', 'vendor.name')
                    ->orderByDesc(\DB::raw('SUM(B.amount)'))
                    ->get();
        }

        return view('laporan_vendor.index',compact('tahun','result'));
    }

    public function chart(Request $request)
    {
        if($request->year)
        {
            $tahun = $request->year;
        }else{
            $tahun = now()->format('Y');
        }

        if($request->by != null){
            $by = $request->by;
        }else{
            $by = "Tonase";
        }

        if($by == 'Tonase' )
        {
            $result = Vendor::join('project_pekerjaan as B', 'vendor.id', '=', 'B.id_vendor')
                        ->join('project as C', function ($join) use ($tahun) {
                            $join->on('B.id_project', '=', 'C.id')
                                ->whereYear('C.created_at', '=', $tahun);
                        })
                        ->select('vendor.id', 'vendor.name', DB::raw('SUM(B.amount) as tonase'))
                        ->groupBy('vendor.id', 'vendor.name')
                        ->orderByDesc(DB::raw('SUM(B.amount)'))
                        ->get();
        }else{
            $result = Vendor::join('project_pekerjaan as B', 'vendor.id', '=', 'B.id_vendor')
                    ->join('project as C', function ($join) use ($tahun) {
                        $join->on('B.id_project', '=', 'C.id')
                            ->whereRaw('YEAR(C.created_at) = ?', [$tahun]);
                    })
                    ->select('vendor.id', 'vendor.name', \DB::raw('SUM(B.amount) as tonase'))
                    ->groupBy('vendor.id', 'vendor.name')
                    ->orderByDesc(\DB::raw('SUM(B.amount)'))
                    ->get();
        }

        return response()->json($result);
    }

    public function detail(Request $request)
    {
        if ($request->ajax()) {
            $data = ProjectPekerjaan::with('vendors')
            ->where('id_vendor',$request->id)
            ->filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('code', function($data){
                return $data->projects->code ?? '';
            })
            ->addColumn('nama_project', function($data){
                return $data->projects->nama_project ?? '';
            })
            ->addColumn('jumlah_project', function($data){
                return $data->total ?? '0';
            })
            ->addColumn('nilai_tagihan', function($data){
                $harga_vendor = $data->harga_vendor;
                if (is_numeric($harga_vendor)) {
                    return 'Rp ' . number_format($harga_vendor, 0, ',', '.');
                } else {
                    return 'Rp 0000';
                }
            })
            ->addColumn('tanggal_mulai', function($data){
                return $data->projects->start_project ;
            })
            ->addColumn('tanggal_selesai', function($data){
                return $data->projects->actual_selesai ?? '-';
            })
            ->addColumn('status_project', function($data){
                if($data->projects->status == 1){
                    $status = '<span style="color: blue;">Request</span>';
                }else if($data->projects->status == 2){
                    $status = '<span style="color: yellow;">Proses</span>';
                }else if($data->projects->status == 3){
                    $status = '<span style="color: green;">Done</span>';
                }else if($data->projects->status == 99){
                    $status = '<span style="color: red;">Cancel</span>';
                }else{
                    $status = '-';
                }
                return $status;
            })
            ->rawColumns(['status_project','tanggal_mulai','nilai_tagihan'])
            ->make(true);                    
        }

        $data = vendor::find($request->id);

        return view('laporan_vendor.detail', compact('data'));
    }

    public function export(Request $request)
    {
        $data = Vendor::filter($request)->get();
        
        foreach($data as $value){
            if($value->projectPekerjaan)
            {
                $value['total_project'] = $value->projectPekerjaan->count();
            }else{
                $value['total_project'] = 0;
            }

            $nilai_tagihan = 0;
            
            if ($value->projectPekerjaan) {
                foreach($value->projectPekerjaan as $values){
        
                    if ($values) {
                        $nilai_tagihan += $values->harga_vendor * $values->qty;
                    }
                    
                }
            }
        
            $value['nilai_tagihan'] = 'Rp '. number_format($nilai_tagihan, 0, ',', '.');
        }

        return Excel::download(new ExportReportVendor($data), 'List Report Vendor.xlsx');
    }

    public function exportDetail(Request $request)
    {
        $data = ProjectPekerjaan::with(['vendors','projects'])
                ->where('id_vendor',$request->id)
                ->filter($request)
                ->get();
        
        foreach($data as $value){
            $harga_vendor = $value->harga_vendor;
            if (is_numeric($harga_vendor)) {
                $value['nilai_tagihan']  = 'Rp ' . number_format($harga_vendor, 0, ',', '.');
            } else {
                $value['nilai_tagihan']  = 'Rp 0000';
            }
            
            if ($value->projects) {
                if($value->projects->status == 1){
                    $value['status'] = 'Request';
                }else if($value->projects->status == 2){
                    $value['status'] = 'Proses';
                }else if($value->projects->status == 3){
                    $value['status'] = 'Done';
                }else{
                    $value['status'] = '-';
                }
            }
        }

        return Excel::download(new ExportReportVendorDetail($data), 'List Report Vendor Detail.xlsx');
    }
}
