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
            if ($request->filled('daterange')) {
                list($start_date, $end_date) = explode(' - ', $request->input('daterange'));
                $query->whereBetween('created_at', [$start_date, $end_date]);
            }
        }])
        ->when($request->filled('customer_id'), function ($query) use ($request) {
            $query->whereHas('projects', function ($innerQuery) use ($request) {
                $innerQuery->where('id_customer', $request->customer_id);
            });
        })
        ->orderBy('name','asc')
        ->get();

        foreach ($datas as $value) {
            $filteredProjects = $value->projects;
        
            if ($filteredProjects->isNotEmpty()) {
                $totalHargaCustomer = 0;
                $id = '';
                $filteredProjectCount = 0;
        
                foreach ($filteredProjects as $project) {
                    $id = $project->id_customer;
        
                    $isMatchingCustomerId = !$request->filled('customer_id') || $project->id_customer == $request->customer_id;
        
                    if ($request->filled('daterange') && strpos($request->input('daterange'), ' - ') !== false) {
                        list($start_date, $end_date) = explode(' - ', $request->input('daterange'));
        
                        $isWithinDateRange = strtotime($project->created_at) >= strtotime($start_date) && strtotime($project->created_at) <= strtotime($end_date);
                    } else {
                        $isWithinDateRange = true;
                    }
        
                    if ($isMatchingCustomerId && $isWithinDateRange) {
                        $filteredProjectCount++;
        
                        foreach ($project->progress as $vals) {
                            $progress = $vals ?? null;
        
                            if ($progress) {
                                $totalHargaCustomer += $progress->harga_customer * $progress->amount;
                            }
                        }
                    }
                }
        
                $value['total_project'] = $filteredProjectCount;
                $value['detail_url'] = route('laporan_customer.detail', [$id, 'daterange' => $request->daterange]);
                $value['totalHargaCustomer'] = 'Rp ' . number_format($totalHargaCustomer, 0, ',', '.');
            } else {
                $value['total_project'] = 0;
                $value['totalHargaCustomer'] = 0;
            }
            $value['eye_image_url'] = "/assets/images/eye.svg";
        }

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

        $data = ProjectPekerjaan::with(['projects'])
        ->when($request->filled('customer_id'), function ($query) use ($request) {
            $query->whereHas('projects', function ($innerQuery) use ($request) {
                $innerQuery->where('id_customer', $request->customer_id);
            });
        })
        ->when($request->filled('daterange'), function ($query) use ($request) {
            $query->whereHas('projects', function ($innerQuery) use ($request) {
                list($start_date, $end_date) = explode(' - ', $request->input('daterange'));
                return $innerQuery->whereBetween('created_at', [$start_date, $end_date]);
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
      
        foreach($result as $keyId => $value){
            $price_project = [];
            foreach($value as $keyDate => $item) {
                if(!in_array($keyDate, $date))
                    $date[] = $keyDate;
                
                // $price_project[$keyId][] = $item->sum('harga_customer') * $item->sum('qty');
                $price_project[$keyId][] = $item->sum(function ($individualItem) {
                    return ($individualItem->harga_customer ?? 0) * ($individualItem->amount ?? 0);
                });
            }
            $data_customer[] = [
                'name' => $item->first()->projects->customer->name ?? '',
                'data' => $price_project[$keyId]
            ];
        }

        return response()->json([
            'date' => array_values($date),
            'data_customer' => $data_customer
        ]);

        // $price_project = [];

        // foreach ($result as $keyId => $value) {
        //     $price_project[$keyId] = [];

        //     foreach ($value as $keyDate => $item) {
        //         if (!in_array($keyDate, $date)) {
        //             $date[] = $keyDate;
        //         }

        //         foreach ($item as $singleItem) {
        //             $total = 0;
        //             $total += $singleItem->harga_customer * $singleItem->qty;
        //             $price_project[$keyId][] = $total;
        //         }
        //     }

        //     $data_customer[] = [
        //         'name' => $item->first()->projects->customer->name ?? '',
        //         'data' => $price_project[$keyId]
        //     ];
        // }

        // return response()->json([
        //     'date' => array_values($date),
        //     'data_customer' => $data_customer
        // ]);
    }
    
    public function detail(Request $request)
    {
        if ($request->ajax()) {
            $data = OnRequest::where('id_customer', $request->id)
                    ->when($request->daterange, function ($query) use ($request) {
                        list($start_date, $end_date) = explode(' - ', $request->daterange);
                        return $query->whereBetween('created_at', [$start_date, $end_date]);
                    }) 
                    ->filter($request)
                    ->get();

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('code', function($data){
                return $data->code ?? '';
            })
            ->addColumn('nama_project', function($data){
                return $data->nama_project ?? '';
            })->addColumn('nilai_project', function ($data) {
                $totalHarga = 0;
        
                foreach ($data->progress as $progress) {
                    $totalHarga += $progress->harga_customer * $progress->amount;
                }

                if (is_numeric($totalHarga)) {
                    return 'Rp ' . number_format($totalHarga, 0, ',', '.');
                } else {
                    return 'Rp 0000';
                }
        
            })
            ->addColumn('tanggal_mulai', function($data){
                return $data && $data->created_at ? $data->created_at->format('d M Y') : '';
            })
            ->addColumn('tanggal_selesai', function($data){
                return $data && $data->actual_selesai ? $data->actual_selesai->format('d M Y') : '';
            })            
            ->addColumn('status_project', function($data){
                if($data->status == 1){
                    $status = '<span style="color: blue;">Progress</span>';
                }else if($data->status == 2){
                    $status = '<span style="color: green;">Complete</span>';
                }else{
                    $status = '-';
                }
                return $status;
            })
            ->addColumn('action', function($data){
                $btnDetail = '';
                if(Can('laporan_customer-detail')) {
                    $btnDetail = '<a href="'.route('laporan_customer_detail.detail', $data->id).'" class="btn btn-warning btn-sm">
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

        $data = OnRequest::where('id_customer',$request->id)->first();
        $data['daterange'] = $request->daterange;

        return view('laporan_customer.detail', compact('data'));
    }

    public function export(Request $request)
    {
        $data = Customer::has('projects')
        ->with(['projects' => function ($query) use ($request) {
            if ($request->filled('daterange')) {
                list($start_date, $end_date) = explode(' - ', $request->input('daterange'));
                $query->whereBetween('created_at', [$start_date, $end_date]);
            }
        }])
        ->when($request->filled('customer_id'), function ($query) use ($request) {
            $query->whereHas('projects', function ($innerQuery) use ($request) {
                $innerQuery->where('id_customer', $request->customer_id);
            });
        })
        ->orderBy('name','asc')
        ->get();

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
                            $totalHargaCustomer += $progress->harga_customer * $progress->amount;
                        }
                    }
                }
            }
        
            $value['totalHargaCustomer'] = 'Rp '. number_format($totalHargaCustomer, 0, ',', '.');
        }

        return Excel::download(new ExportReportCustomer($data), 'Report Customer.xlsx');
    }

    public function exportDetail(Request $request)
    {
        $data = OnRequest::has('progress')
                ->where('id_customer', $request->id)
                ->filter($request)
                ->get();

        foreach($data as $value){
            $totalHarga = 0;
            foreach ($value->progress as $progress) {
                $totalHarga += $progress->harga_customer * $progress->amount;
            }

            if (is_numeric($totalHarga)) {
                $value['nilai_project'] = 'Rp ' . number_format($totalHarga, 0, ',', '.');
            } else {
                $value['nilai_project'] = 'Rp 0000';
            }

            if($value->status == 1){
                $value['stat'] = 'Progress';
            }else if($value->status == 2){
                $value['stat'] = 'Complete';
            }else{
                $value['stat'] = '-';
            }
        }
  

        return Excel::download(new ExportReportCustomerDetail($data), 'Report Customer Detail.xlsx');
    }

    
    // public function chart(Request $request){
    //     if($request->year)
    //     {
    //         $tahun = $request->year;
    //     }else{
    //         $tahun = now()->format('Y');
    //     }
       
    //     $totalHargaPerBulan = array_fill(0, 12, 0);

    //     $data = ProjectPekerjaan::selectRaw('MONTH(created_at) as month, SUM(harga_customer) as total_harga')
    //         ->whereYear('created_at', $tahun)
    //         ->groupBy('month')
    //         ->orderBy('month')
    //         ->get();

    //         $totalHarga = [];

    //         foreach ($data as $item) {
    //             $totalHargaPerBulan[$item->month - 1] = $item->total_harga;
    //         }

    //     $totalHargaData = json_encode($totalHargaPerBulan, JSON_NUMERIC_CHECK);

    //     return response()->json([
    //         'totalHargaData' => $totalHargaData
    //     ]);
    // }
}
