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
use App\Models\ProjectAdmin;
use App\Models\ProjectEngineer;
use App\Models\StatusSurvey;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ProjectPlanner;

class OnRequestController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $user = Auth::user();
                $userRole = $user->role->name ?? '';
                $karyawanId = $user->karyawan->id ?? null;

                // Base query dengan eager loading
                $query = OnRequest::with(['kapal:id,name', 'customer:id,name', 'survey:id,name']);

                // Filter berdasarkan role user
                switch ($userRole) {
                    case 'Project Manager':
                    case 'PM':
                        $pm = ProjectManager::where('id_karyawan', $karyawanId)->first();
                        $query = $pm ? $query->where('pm_id', $pm->id) : $query->where('id', 0);
                        break;

                    case 'Project Admin':
                    case 'PA':
                        $pa = ProjectAdmin::where('id_karyawan', $karyawanId)->first();
                        $query = $pa ? $query->where('pa_id', $pa->id) : $query->where('id', 0);
                        break;

                    case 'SPV Project Planner':
                        $pp = ProjectPlanner::where('id_karyawan', $karyawanId)->first();
                        $query = $pp ? $query->where('pp_id', $pp->id) : $query->where('id', 0);
                        break;

                    case 'BOD':
                    case 'BOD1':
                    case 'Super Admin':
                    case 'Administrator':
                    case 'Staff Finance':
                    case 'SPV Finance':
                        $pmIds = ProjectManager::pluck('id')->toArray();
                        $query = !empty($pmIds) ? $query->whereIn('pm_id', $pmIds) : $query->where('id', 0);
                        break;

                    default:
                        $query->where('id', 0); // No access
                        break;
                }

                $data = $query->where('status', 1)
                            ->filter($request)
                            ->orderBy('created_at', 'desc')
                            ->get();

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('survey', function($row) {
                        return $row->survey->name ?? '-';
                    })
                    ->addColumn('nama_customer', function($row) {
                        return $row->customer->name ?? '-';
                    })
                    ->addColumn('jenis_kapal', function($row) {
                        return $row->kapal->name ?? '-';
                    })
                    ->addColumn('tanggal_request', function($row) {
                        return $row->created_at ? $row->created_at->format('d M Y') : '-';
                    })
                    ->addColumn('action', function($row) {
                        $buttons = '';
                        if (Can('on_request-detail')) {
                            $buttons .= '<a href="' . route('on_request.detail', $row->id) . '" 
                                        class="btn btn-warning btn-sm me-1" title="Detail">
                                      <span>
                                        <i><img src="'.asset('assets/images/eye.svg').'" style="width: 15px;"></i>
                                    </span>
                                    </a>';
                        }
                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->make(true);

            } catch (\Exception $e) {
                return response()->json(['error' => 'Terjadi kesalahan saat memuat data'], 500);
            }
        }

        $customer = Customer::select('id', 'name')->orderBy('name')->get();
        $jenis_kapal = JenisKapal::select('id', 'name')->orderBy('name')->get();
        $status = StatusSurvey::select('id', 'name')->orderBy('name')->get();

        return view('on_request.index', compact('customer', 'jenis_kapal', 'status'));
    }

    public function create()
    {
        if(Auth::user()->role->name !== 'SPV Project Planner'){
            return redirect()->back()->with('error', 'Hanya Project Planner yang bisa menambahkan Project Baru');
        }

        $customer   = Customer::orderBy('name','asc')->get();
        $lokasi     = LokasiProject::orderBy('name','asc')->get();
        $jenis_kapal= JenisKapal::orderBy('name','asc')->get();
        $status     = StatusSurvey::orderBy('name','asc')->get();
        $pmAuth     = Auth::user()->role->name ?? '';
        $pm         = User::whereIn('id_role', [2,3])->with(['karyawan'])->get();
        $pe         = User::whereIn('id_role', [4, 11])->with(['karyawan'])->get();
        $pa         = User::whereIn('id_role', [5, 13])->with(['karyawan'])->get();
       
        return view('on_request.create',compact('customer','lokasi','jenis_kapal','status','pmAuth','pm','pe','pa'));
    }

    public function edits(Request $request)
    {
        $data = Customer::find($request->id);

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_project'          => 'required',
            'lokasi_project'        => 'required',
            'contact_person'        => 'required',
            'nomor_contact_person'  => 'required',
            'id_customer'           => 'required',
            'displacement'          => 'required',
            'jenis_kapal'           => 'required',
            'pa_id_1'               => 'required',
            'pm_id_1'               => 'required',
            'pe_id_1'               => 'required',
        ]);

        $code = 'PJ' . now()->format('Y') . "-";
        $existingNumbers = OnRequest::where('code', 'LIKE', $code . '%')->pluck('code')->toArray();

        $maxNumber = 0;
        foreach ($existingNumbers as $existingNumber) {
            $number = intval(substr($existingNumber, -3));
            $maxNumber = max($maxNumber, $number);
        }

        $newNumber = str_pad($maxNumber + 1, 4, '0', STR_PAD_LEFT);

        $newNoSpk = $code . $newNumber;

        $pa = ProjectAdmin::firstOrCreate([
            'id_karyawan' => $request->input('pa_id_1'),
        ]);

        $pm = ProjectManager::firstOrCreate([
            'id_karyawan' => $request->input('pm_id_1'),
        ]);

        $pe = ProjectEngineer::firstOrCreate([
            'id_karyawan' => $request->input('pe_id_1'),
        ]);

        $pp = ProjectPlanner::firstOrCreate([
            'id_karyawan' => Auth::user()->id,
        ]);

        $data                       = New OnRequest();
        $data->code                 = $newNoSpk;
        $data->nama_project         = $request->input('nama_project');
        $data->id_customer          = $request->input('id_customer');
        $data->id_lokasi_project    = $request->input('lokasi_project');
        $data->contact_person       = $request->input('contact_person');
        $data->nomor_contact_person = $request->input('nomor_contact_person');
        $data->displacement         = $request->input('displacement');
        $data->id_jenis_kapal       = $request->input('jenis_kapal');
        $data->pa_id                = $pa->id ?? '';
        $data->pm_id                = $pm->id ?? '';
        $data->pe_id_1              = $pe->id ?? '';
        $data->status_survey        = $request->input('status_survey');
        $data->status               = 1;
        $data->pp_id                = $pp->id ?? '';
        $data->save();

        return redirect()->route('on_request.detail', ['id' => $data->id])
                        ->with('success', 'Data saved successfully');
    }

    public function tableData($id) 
    {
        $pmAuth         = Auth::user()->role->name ?? '';
        $keluhans        = Keluhan::where('on_request_id',$id)->get();
        $count          = $keluhans->whereNotNull('id_pm_approval')->whereNotNull('id_bod_approval')->count();
        $keluhan        = count($keluhans);

        return view('on_request.tableData', compact('keluhan','count', 'pmAuth','keluhans'));
    }

    public function detail(Request $request)
    {
        $data           = OnRequest::find($request->id);
        $getCustomer    = Customer::find($data->id_customer);
        $customer       = Customer::orderBy('name','asc')->get();
        $lokasi         = LokasiProject::orderBy('name','asc')->get();
        $jenis_kapal    = JenisKapal::orderBy('name','asc')->get();
        $pm         = User::whereIn('id_role', [2,3])->with(['karyawan', 'karyawan.pm'])->get();
        $pe         = User::whereIn('id_role', [4, 11])->with(['karyawan', 'karyawan.pe'])->get();
        $pa         = User::whereIn('id_role', [5, 13])->with(['karyawan', 'karyawan.pa'])->get();

        $vendor         = Vendor::orderBy('name','asc')->get();
        $pmAuth         = Auth::user()->role->name ?? '';
        $keluhan        = Keluhan::where('on_request_id', $request->id)->get();
        $count          = $keluhan->whereNotNull('id_pm_approval')->whereNotNull('id_bod_approval')->count();
        $status         = StatusSurvey::orderBy('name','asc')->get();
        $keluhan        = count($keluhan);

        return view('on_request.detail', Compact('status','keluhan','count','data','customer','lokasi','jenis_kapal','getCustomer','pe','vendor','pmAuth', 'pa', 'pm'));
    }

    public function updated(Request $request)
    {
        $request->validate([
            'nama_project'  => 'required',
            'id_customer'   => 'required',
            'pe_id_1'       => 'required'
        ]);

        $pa = ProjectAdmin::firstOrCreate([
            'id_karyawan' => $request->input('pa_id'),
        ]);

        $pm = ProjectManager::firstOrCreate([
            'id_karyawan' => $request->input('pm_id'),
        ]);

        $pe = ProjectEngineer::firstOrCreate([
            'id_karyawan' => $request->input('pe_id_1'),
        ]);

        $data                       = OnRequest::find($request->id);
        $data->nama_project         = $request->input('nama_project');
        $data->id_customer          = $request->input('id_customer');
        $data->id_lokasi_project    = $request->input('lokasi_project');
        $data->contact_person       = $request->input('contact_person');
        $data->nomor_contact_person = $request->input('nomor_contact_person');
        $data->displacement         = $request->input('displacement');
        $data->id_jenis_kapal       = $request->input('jenis_kapal');
        $data->pe_id_1              = $pe->id ?? '';
        $data->pm_id                = $pm->id ?? '';
        $data->pa_id                = $pa->id ?? '';
        $data->status_survey        = $request->input('status_survey');
        $data->save();

        return redirect()->route('on_request.detail', ['id' => $request->id])
                        ->with('success', 'Data saved successfully');
    }

    public function export(Request $request)
    {
        $cekRole = Auth::user()->role->name;
        $cekId = Auth::user()->id_karyawan;
        $cekPm = ProjectAdmin::where('id_karyawan',$cekId)->first();
        $cekPa  = ProjectManager::where('id_karyawan', $cekId)->first();
        $result = ProjectManager::get()->toArray();

        $data = OnRequest::with(['kapal', 'customer']);
         
        if ($cekRole == 'Project Manager') {
            $data->where('pm_id', $cekPa->id);
        }else if ($cekRole == 'Project Admin') {
            if($cekPm){
                $data->where('pm_id', $cekPm->id_pm);
            }
        }else if ($cekRole == 'BOD' 
                    || $cekRole == 'Super Admin' 
                    || $cekRole == 'Administator' 
                    || $cekRole == 'Staff Finance'
                    || $cekRole == 'SPV Finance') {
            if($result){
                $data->whereIn('pm_id', array_column($result, 'id'));
            }
        }else{
            $data->where('pm_id', '');
        }
        
        $data = $data->where('status',1)
                    ->orWhere('status',null)
                    ->filter($request)
                    ->orderBy('created_at', 'desc')
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

    public function stores(Request $request)
    {
        $request->validate([
            'name'                  => 'required',
            'alamat'                => 'required',
            'contact_person'        => 'required',
            'nomor_contact_person'  => 'required',
        ]);

        $data = New Customer();
        $data->name                     = $request->input('name');
        $data->alamat                   = $request->input('alamat');
        $data->contact_person           = $request->input('contact_person');
        $data->nomor_contact_person     = $request->input('nomor_contact_person');
        $data->email                    = $request->input('email');
        $data->npwp                     = $request->input('npwp');
        $data->save();

        $data= Customer::find($data->id);

        return response()->json($data);
    }
}
