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
        $datas = Vendor::has('projectPekerjaan')
        ->when($request->filled('vendor_id'), function ($query) use ($request) {
            $query->whereHas('projectPekerjaan', function ($innerQuery) use ($request) {
                $innerQuery->where('id_vendor', $request->vendor_id);
            });
        })
        ->when($request->filled('daterange'), function ($query) use ($request) {
            list($start_date, $end_date) = explode(' - ', $request->input('daterange'));
            $query->whereHas('projectPekerjaan', function ($innerQuery) use ($request) {
                $reportType = $request->report_by;

                switch ($reportType) {
                    case 'tahun':
                        $innerQuery->whereYear('created_at', $start_date);
                        break;
                    case 'tanggal':
                        $innerQuery->whereDate('created_at', '>=', $start_date)
                            ->whereDate('created_at', '<=', $end_date);
                        break;
                    case 'bulan':
                    default:
                        $innerQuery->whereMonth('created_at', $start_date)
                            ->whereYear('created_at', $end_date);
                        break;
                }
            });
        })
        ->get();
            
        foreach($datas as $value){
            if($value->projectPekerjaan)
            {
                $value['total_project'] = $value->projectPekerjaan->count();
                $value['detail_url'] = route('laporan_vendor.detail', $value->id);
            }else{
                $value['total_project'] = 0;
            }

            $value['eye_image_url'] = "/assets/images/eye.svg";

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
        $datas = $datas->sortByDesc('nilai_tagihan')->values();

        if($request->report_by){
            return response()->json([
                'datas' => $datas
            ]);
        }
        
        $vendors = Vendor::has('projectPekerjaan')->get();
        return view('laporan_vendor.index', compact('vendors','datas'));
        
    }

    public function dataCharts(Request $request)
    {
        if($request->report_by != null)
        {
            $report_by = $request->report_by;
        }else{
            $report_by = 'tahun';
        }
        $tahun = now()->format('Y');

        $data = ProjectPekerjaan::with(['vendors'])
        ->when($request->filled('vendor_id'), function ($query) use ($request) {
            $query->where('id_vendor', $request->vendor_id);
        })
        ->when($request->filled('daterange'), function ($query) use ($request) {
            list($start_date, $end_date) = explode(' - ', $request->input('daterange'));
            return $query->whereBetween('created_at', [$start_date, $end_date]);
        })  
        ->get()
        ->groupBy('id_vendor');

        $data_customer = [];
        $date = [];
        $result = $data->map(function ($groupedItems) use ($report_by) {
            return $groupedItems->groupBy(function ($item) use ($report_by) {
                switch ($report_by) {
                    case 'bulan':
                        return $item->created_at->format('Y-m');
                    case 'tahun':
                        return $item->created_at->format('Y');
                    case 'tanggal':
                    default:
                        return $item->created_at->toDateString();
                }
            });
        });
        
        foreach($result as $keyId => $value){
            $price_project = [];
            foreach($value as $keyDate => $item) {
                if(!in_array($keyDate, $date))
                    $date[] = $keyDate;
                
                // $price_project[$keyId][] = $item->sum('harga_vendor') * $item->sum('qty');
                $price_project[$keyId] = [];
                $price_project[$keyId][] = $item->sum(function ($individualItem) {
                    return ($individualItem->harga_vendor ?? 0) * ($individualItem->qty ?? 0);
                });

            }
            $data_customer[] = [
                'name' => $item->first()->vendors->name ?? '',
                'data' => $price_project[$keyId],
            ];
        }

        return response()->json([
            'date' => array_values($date),
            'data_vendor' => $data_customer
        ]);
    }

    public function chart(Request $request)
    {
        if($request->year)
        {
            $tahun = $request->year;
        }else{
            $tahun = now()->format('Y');
        }

        if($request->type != null){
            $type = $request->type;
        }else{
            $type = "Tonase";
        }

        if($type == 'Tonase' )
        {
            $result = Vendor::join('project_pekerjaan as B', 'vendor.id', '=', 'B.id_vendor')
                        ->join('project as C', function ($join) use ($tahun) {
                            $join->on('B.id_project', '=', 'C.id')
                                ->where('B.id_kategori', '=', 3)
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
                            ->where('B.id_kategori', '=', 2)
                            ->whereRaw('YEAR(C.created_at) = ?', [$tahun]);
                    })
                    ->select('vendor.id', 'vendor.name', \DB::raw('SUM(B.amount) as tonase'))
                    ->groupBy('vendor.id', 'vendor.name')
                    ->orderByDesc(\DB::raw('SUM(B.amount)'))
                    ->get();
        }

        if(count($result) === 0){
            $result = [
                [
                    'id' => 0,
                    'name' => 'Not Available',
                    'tonase' => '0.00'
                ]
            ];
        }else{
            $result = $result;
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
