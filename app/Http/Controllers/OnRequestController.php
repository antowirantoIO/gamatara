<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OnRequest;
use App\Models\Customer;
use App\Models\LokasiProject;
use App\Models\JenisKapal;
use App\Models\Keluhan;
use App\Models\ProjectManager;

class OnRequestController extends Controller
{
    public function index()
    {
        $data = OnRequest::with(['kapal','customer'])->get();

        return view('on_request.index',compact('data'));
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
            'nama_project'   => 'required',
        ]);

        $code = 'P'.now()->format('d').now()->format('m').now()->format('y')."-";
        $projectCode = OnRequest::where('code', 'LIKE', '%'.$code.'%')->count();
        $randInt = '00001';
        if ($projectCode >= 1) {
            $count = $projectCode+1;
            $randInt = '0000'.(string)$count;
        }
        $randInt = substr($randInt, -5);

        $getCustomer = Customer::where('name',$request->input('id_customer'))->first();

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

        $keluhanJson = $request->input('keluhan');
        $keluhanArray = json_decode($keluhanJson);
        
        if (!empty($keluhanArray)) {
            foreach ($keluhanArray as $keluhanText) {
                $keluhan = new Keluhan();
                $keluhan->keluhan = $keluhanText;
                $keluhan->on_request_id = $data->id;
                $keluhan->save();
            }
        }

        return redirect(route('on_request'))
                    ->with('success', 'Data berhasil disimpan');
    }

    public function detail(Request $request)
    {
        $data           = OnRequest::find($request->id);
        $getCustomer    = Customer::find($data->id_customer);
        $customer       = Customer::get();
        $lokasi         = LokasiProject::get();
        $jenis_kapal    = JenisKapal::get();
        $pm             = ProjectManager::with(['karyawan'])->get();
        $keluhan        = Keluhan::where('on_request_id',$data->id)->get();

        return view('on_request.detail', Compact('data','customer','lokasi','jenis_kapal','getCustomer','keluhan','pm'));
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
}
