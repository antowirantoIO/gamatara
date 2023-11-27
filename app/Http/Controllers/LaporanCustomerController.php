<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ExportLaporanCustomer;
use App\Models\Customer;
use App\Models\OnRequest;
use App\Models\ProjectPekerjaan;
use App\Exports\ExportReportCustomer;
use App\Exports\ExportReportCustomerDetail;
use DB;
use Carbon\Carbon;

class LaporanCustomerController extends Controller
{
    public function index(Request $request)
    {

    $datas = Customer::has('projects')
        ->with(['projects' => function ($query) use ($request) {
            $query->with(['progress' => function ($progressQuery) use ($request) {
                if ($request->report_by == 'tahun') {
                    $start_date = Carbon::parse($request->start_date)->startOfYear();
                    $end_date = Carbon::parse($request->end_date)->endOfYear();
                    $progressQuery->whereBetween('created_at', [$start_date, $end_date]);
                } elseif ($request->report_by == 'bulan') {
                    $start_date = Carbon::parse($request->start_date)->startOfMonth();
                    $end_date = Carbon::parse($request->end_date)->endOfMonth();
                    $progressQuery->whereBetween('created_at', [$start_date, $end_date]);
                } elseif ($request->report_by == 'tanggal') {
                    $start_date = Carbon::parse($request->start_date)->startOfDay();
                    $end_date = Carbon::parse($request->end_date)->endOfDay();
                    $progressQuery->whereBetween('created_at', [$start_date, $end_date]);
                }
            }]);
        }])
        ->get();

        foreach ($datas as $value) {
            if ($value->projects) {
                $value['total_project'] = $value->projects->count();
            } else {
                $value['total_project'] = 0;
            }

            $totalHargaCustomer = 0;

            if ($value->projects) {
                foreach ($value->projects as $values) {
                    foreach ($values->progress as $project) {
                        $progress = $project ?? null;

                        if ($progress) {
                            $totalHargaCustomer += $progress->harga_customer * $progress->qty;
                        }
                    }
                }
            }

            $value['totalHargaCustomer'] = 'Rp ' . number_format($totalHargaCustomer, 0, ',', '.');
        }

        $datas = $datas->sortByDesc('totalHargaCustomer')->values();
        
        if($request->report_by){
            return response()->json([
                'datas' => $datas
            ]);
        }

        $customers = Customer::has('projects')->get();

        return view('laporan_customer.index', compact('customers','datas'));
    }

    public function dataChart(Request $request)
    {
        if($request->report_by != null)
        {
            $report_by = $request->report_by;
        }else{
            $report_by = 'tahun';
        }
        $tahun = now()->format('Y');

        $data = ProjectPekerjaan::with(['projects'])
        ->when($request->filled('customer_id'), function ($query) use ($request) {
            $query->whereHas('projects', function ($innerQuery) use ($request) {
                $innerQuery->where('id_customer', $request->customer_id);
            });
        })
        ->get()
        ->groupBy('projects.id_customer');

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
        // dd($result);
        foreach($result as $keyId => $value){
            $price_project = [];
            foreach($value as $keyDate => $item) {
                if(!in_array($keyDate, $date))
                    $date[] = $keyDate;
                
                $price_project[$keyId][] = $item->sum('harga_customer') * $item->sum('qty');
            }
            // $all_price_project = [];
            // foreach($value as $key => $item) {
            //     $all_price_project[] = $item->sum('harga_customer') * $item->sum('qty');
            // }
            // dd($price_project);
            $data_customer[] = [
                'name' => $item->first()->projects->customer->name ?? '',
                'data' => $price_project[$keyId]
            ];
        }
        // dd($data_customer);

        return response()->json([
            'date' => array_values($date),
            'data_customer' => $data_customer
        ]);
    }

    public function chart(Request $request){
        if($request->year)
        {
            $tahun = $request->year;
        }else{
            $tahun = now()->format('Y');
        }
       
        $totalHargaPerBulan = array_fill(0, 12, 0);

        $data = ProjectPekerjaan::selectRaw('MONTH(created_at) as month, SUM(harga_customer) as total_harga')
            ->whereYear('created_at', $tahun)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

            $totalHarga = [];

            foreach ($data as $item) {
                $totalHargaPerBulan[$item->month - 1] = $item->total_harga;
            }

        $totalHargaData = json_encode($totalHargaPerBulan, JSON_NUMERIC_CHECK);

        return response()->json([
            'totalHargaData' => $totalHargaData
        ]);
    }
    
    public function detail(Request $request)
    {
        if ($request->ajax()) {
            $cek = OnRequest::where('id', $request->id)->get();
            $cekIds = $cek->pluck('id')->toArray();
            $data = ProjectPekerjaan::with('projects')->whereIn('id_project',$cekIds)
                    ->addSelect(['total' => OnRequest::selectRaw('count(*)')
                        ->whereColumn('project_pekerjaan.id_project', 'project.id')
                        ->groupBy('id_customer')
                    ])
                    ->filter($request);


            return Datatables::of($data)->addIndexColumn()
            ->addColumn('code', function($data){
                return $data->projects->code ?? '';
            })
            ->addColumn('nama_project', function($data){
                return $data->projects->nama_project ?? '';
            })
            ->addColumn('jumlah_project', function($data){
                return $data->total;
            })
            ->addColumn('nilai_project', function($data){
                $harga_customer = $data->harga_customer;
                if (is_numeric($harga_customer)) {
                    return 'Rp ' . number_format($harga_customer, 0, ',', '.');
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
                    $status = '<span style="color: yellow;">Progress</span>';
                }else if($data->projects->status == 3){
                    $status = '<span style="color: green;">Complete</span>';
                }else{
                    $status = '-';
                }
                return $status;
            })
            ->addColumn('action', function($data){
                $btnDetail = '';
                if(Can('laporan_customer-detail')) {
                    $btnDetail = '<a href="'.route('laporan_customer_detail.detail', $data->projects->id).'" class="btn btn-warning btn-sm">
                                    <span>
                                        <i><img src="'.asset('assets/images/eye.svg').'" style="width: 15px;"></i>
                                    </span>
                                </a>';
                }

                return $btnDetail;
            })
            ->rawColumns(['action','status_project','tanggal_mulai','jumlah_project','nilai_project'])
            ->make(true);                    
        }

        $data = OnRequest::where('id',$request->id)->first();

        return view('laporan_customer.detail', compact('data'));
    }

    public function export(Request $request)
    {
        $data = Customer::has('projects')->with('projects','projects.progress')->get();
        
        foreach($data as $value){
            if($value->projects)
            {
                $value['total_project'] = $value->projects->count();
            }else{
                $value['total_project'] = 0;
            }

            $totalHargaCustomer = 0;
        
            if ($value->projects) {
                foreach($value->projects as $values){
                    foreach ($values->progress as $project) {
                        $progress = $project ?? null;
            
                        if ($progress) {
                            $totalHargaCustomer += $progress->harga_customer * $progress->qty;
                        }
                    }
                }
            }
        
            $value['totalHargaCustomer'] = 'Rp '. number_format($totalHargaCustomer, 0, ',', '.');
        }

        return Excel::download(new ExportReportCustomer($data), 'List Report Customer.xlsx');
    }

    public function exportDetail(Request $request)
    {
        $data = ProjectPekerjaan::with('projects')->where('id_project',$request->id)
                ->addSelect(['total' => OnRequest::selectRaw('count(*)')
                    ->whereColumn('project_pekerjaan.id_project', 'project.id')
                    ->groupBy('id_customer')
                ])
                ->filter($request)
                ->get();

        foreach($data as $value){
            $harga_customer = $value->harga_customer;
            if (is_numeric($harga_customer)) {
                 $value['nilai_project'] = 'Rp ' . number_format($harga_customer, 0, ',', '.');
            } else {
                 $value['nilai_project'] = 'Rp 0000';
            }

            if($value->projects->status == 1){
                $value['status'] = 'Request';
            }else if($value->projects->status == 2){
                $value['status'] = 'Progress';
            }else if($value->projects->status == 3){
                $value['status'] = 'Complete';
            }else{
                $value['status'] = '-';
            }
        }

        return Excel::download(new ExportReportCustomerDetail($data), 'List Report Customer Detail.xlsx');
    }
}
