<?php

namespace App\Http\Controllers;

use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\ExportListOnRequest;
use App\Exports\ExportDetailOnRequest;
use App\Models\OnRequest;
use App\Models\Customer;
use App\Models\LokasiProject;
use App\Models\JenisKapal;
use App\Models\Keluhan;
use App\Models\ProjectManager;
use App\Models\Vendor;
use Auth;

class OnRequestController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = OnRequest::with(['kapal','customer'])
                    ->filter($request);

            return Datatables::of($data)->addIndexColumn()
            ->addColumn('nama_customer', function($data){
                return $data->customer->name ?? '';
            })
            ->addColumn('jenis_kapal', function($data){
                return $data->kapal->name ?? '';
            })
            ->addColumn('tanggal_request', function($data){
                return $data->created_at ? $data->created_at->format('d-m-Y H:i') : '';
            })
            ->addColumn('action', function($data){
                return '<a href="'.route('on_request.detail', $data->id).'" class="btn btn-warning btn-sm">
                    <span>
                        <i><img src="'.asset('assets/images/eye.svg').'" style="width: 15px;"></i>
                    </span>
                </a>';
            })
            ->rawColumns(['jenis_kapal','nama_customer','tanggal_request','action'])
            ->make(true);                    
        }

        $customer   = Customer::get();
        $jenis_kapal= JenisKapal::get();
        $auth       = Auth::user()->role->name ?? '';

        return view('on_request.index',compact('customer','jenis_kapal','auth'));
    }

    public function create()
    {
        $customer   = Customer::get();
        $lokasi     = LokasiProject::get();
        $jenis_kapal= JenisKapal::get();

        return view('on_request.create',compact('customer','lokasi','jenis_kapal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_project'          => 'required',
            'nama_customer'         => 'required',
            'lokasi_project'        => 'required',
            'contact_person'        => 'required',
            'nomor_contact_person'  => 'required',
            'nama_customer'         => 'required',
            'displacement'          => 'required',
            'jenis_kapal'           => 'required'
        ]);

        $code = 'P'.now()->format('d').now()->format('m').now()->format('y')."-";
        $projectCode = OnRequest::where('code', 'LIKE', '%'.$code.'%')->count();
        $randInt = '00001';
        if ($projectCode >= 1) {
            $count = $projectCode+1;
            $randInt = '0000'.(string)$count;
        }
        $randInt = substr($randInt, -5);

        $getCustomer = Customer::where('name',$request->input('nama_customer'))->first();

        $data                       = New OnRequest();
        $data->code                 = $code.$randInt;
        $data->nama_project         = $request->input('nama_project');
        $data->id_customer          = $getCustomer->id;
        $data->id_lokasi_project    = $request->input('lokasi_project');
        $data->contact_person       = $request->input('contact_person');
        $data->nomor_contact_person = $request->input('nomor_contact_person');
        $data->displacement         = $request->input('displacement');
        $data->id_jenis_kapal       = $request->input('jenis_kapal');
        $data->save();

        // $keluhanJson = $request->input('keluhan');
        // $keluhanArray = json_decode($keluhanJson);
        
        // if (!empty($keluhanArray)) {
        //     foreach ($keluhanArray as $keluhanText) {
        //         $keluhan = new Keluhan();
        //         $keluhan->keluhan = $keluhanText;
        //         $keluhan->on_request_id = $data->id;
        //         $keluhan->save();
        //     }
        // }

        return redirect()->route('on_request.detail', ['id' => $data->id])
                        ->with('success', 'Data berhasil disimpan');
    }

    public function tableData($id) 
    {
        $pmAuth         = Auth::user()->karyawan->role->name ?? '';
        $keluhan        = Keluhan::where('on_request_id',$id)->get();

        return view('on_request.tableData', compact('keluhan', 'pmAuth'));
    }

    public function detail(Request $request)
    {
        $data           = OnRequest::find($request->id);
        $getCustomer    = Customer::find($data->id_customer);
        $customer       = Customer::get();
        $lokasi         = LokasiProject::get();
        $jenis_kapal    = JenisKapal::get();
        $pm             = ProjectManager::with(['karyawan'])->get();
        $vendor         = Vendor::get();
        $pmAuth         = Auth::user()->karyawan->role->name ?? '';
        $keluhan        = Keluhan::where('on_request_id', $request->id)->get();
        $count          = $keluhan->whereNotNull('id_pm_approval')->whereNotNull('id_bod_approval')->count();
        $keluhan        = count($keluhan);

        return view('on_request.detail', Compact('keluhan','count','data','customer','lokasi','jenis_kapal','getCustomer','pm','vendor','pmAuth'));
    }

    public function updated(Request $request)
    {
        $request->validate([
            'nama_project'   => 'required',
        ]);

        $getCustomer = Customer::where('name',$request->input('id_customer'))->first();

        $data                       = OnRequest::find($request->id);
        $data->nama_project         = $request->input('nama_project');
        $data->id_customer          = $getCustomer->id;
        $data->id_lokasi_project    = $request->input('lokasi_project');
        $data->contact_person       = $request->input('contact_person');
        $data->nomor_contact_person = $request->input('nomor_contact_person');
        $data->displacement         = $request->input('displacement');
        $data->id_jenis_kapal       = $request->input('jenis_kapal');
        $data->pm_id                = $request->input('pm_id');
        if($request->input('pm_id')){
            $data->status = 1;
        }
        $data->save();

        return response()->json(['message' => 'success','status' => 200]);
    }

    public function export(Request $request)
    {
        $data = OnRequest::orderBy('nama_project','asc')
                ->filter($request)
                ->get();

        return Excel::download(new ExportListOnRequest($data), 'List On Request.xlsx');
    }

    public function exportDetail(Request $request)
    {
        $data = OnRequest::with(['keluhan'])->orderBy('nama_project','asc')
                ->where('id',$request->id)
                ->first();

        return Excel::download(new ExportDetailOnRequest($data), 'List Detail On Request.xlsx');
    }
}
