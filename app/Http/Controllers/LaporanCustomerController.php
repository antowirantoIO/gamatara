<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ExportLaporanCustomer;
use App\Models\Customer;
use App\Models\OnRequest;

class LaporanCustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('jumlah_project', function($data){
                return '1';
            })
            ->addColumn('nilai_project', function($data){
                return '2';
            })
            ->addColumn('action', function($data){
                return '<a href="'.route('laporan_customer.detail', $data->id).'" class="btn btn-warning btn-sm">
                    <span>
                        <i><img src="'.asset('assets/images/eye.svg').'" style="width: 15px;"></i>
                    </span>
                </a>';
            })
            ->rawColumns(['action'])
            ->make(true);                    
        }

        return view('laporan_customer.index');
    }
    
    public function detail(Request $request)
    {
        $name = OnRequest::where('id_customer',$request->id)->first();

        if ($request->ajax()) {
            $data = OnRequest::where('id_customer',$request->id)->filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('jumlah_project', function($data){
                return '1';
            })
            ->addColumn('nilai_project', function($data){
                return '2';
            })
            ->addColumn('tanggal_request', function($data){
                return $data->created_at ? $data->created_at->format('d-m-Y H:i') : '';
            })
            ->addColumn('status_project', function($data){
                if($data->status == 1){
                    $status = '<span style="color: blue;">Request</span>';
                }else if($data->status == 2){
                    $status = '<span style="color: yellow;">Proses</span>';
                }else if($data->status == 3){
                    $status = '<span style="color: green;">Complete</span>';
                }else if($data->status == 99){
                    $status = '<span style="color: red;">Cancel</span>';
                }else{
                    $status = '-';
                }
                return $status;
            })
            ->addColumn('action', function($data){
                return '<a href="'.route('laporan_customer_detail.detail', $data->id).'" class="btn btn-warning btn-sm">
                    <span>
                        <i><img src="'.asset('assets/images/eye.svg').'" style="width: 15px;"></i>
                    </span>
                </a>';
            })
            ->rawColumns(['action','status_project'])
            ->make(true);                    
        }


        return view('laporan_customer.detail', Compact('name'));
    }
    
}
