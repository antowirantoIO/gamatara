<?php

namespace App\Http\Controllers;

use App\Models\OnRequest;
use App\Models\Pekerjaan;
use App\Models\SubKategori;
use App\Models\Kategori;
use App\Models\ProjectPekerjaan;
use App\Models\SettingPekerjaan;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class OnProgressController extends Controller
{
    public function index()
    {
        $data = OnRequest::get();
        return view('on_progres.index',compact('data'));
    }

    public function edit($id)
    {
        $data = OnRequest::find($id);
        $projects = ProjectPekerjaan::where('id_project',$id)
                                    ->select('id_vendor')
                                    ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                                    ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                                    ->groupBy('status', 'id_vendor')
                                    ->get();
        $pekerjaan = ProjectPekerjaan::where('id_project',$id)
                                    ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                                    ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                                    ->first();
        return view('on_progres.edit',compact('data','projects','pekerjaan'));
    }

    public function addWork($id)
    {
        $works = Kategori::all();
        $vendors = Vendor::all();
        return view('on_progres.request',compact('id','works','vendors'));
    }


    public function requestPost(Request $request)
    {
        $validasi = Validator::make($request->all(),[
            'kategori' => 'required',
            'sub_kategori' => 'required',
            'pekerjaan' => 'required',
            'deskripsi' => 'required',
            'detail' => 'required',
            'length' =>  'required',
            'width' => 'required',
            'thick' => 'required',
            'unit' => 'required',
            'qty' => 'required',
            'amount' => 'required'
        ]);

        if($validasi->fails()){
            return back()->with('error',$validasi->errors()->first());
        }

        foreach($request->pekerjaan as $key => $item){
            ProjectPekerjaan::create([
                'id_project' => $request->id_project,
                'id_kategori' => $request->kategori,
                'id_subkategori' => $request->sub_kategori,
                'id_pekerjaan' => $item,
                'id_vendor' => $request->vendor,
                'deskripsi_subkategori' => $request->nama_pekerjaan,
                'deskripsi_pekerjaan' => $request->deskripsi[$key],
                'detail' => $request->detail[$key],
                'length' => $request->length[$key],
                'width' => $request->width[$key],
                'thick' => $request->thick[$key],
                'unit' => $request->unit[$key],
                'qty' => $request->qty[$key],
                'amount' => $request->amount[$key],
            ]);
        }

        return redirect()->to(route('on_progress.edit',$request->id_project))->with('success','Data Berhasil Di Simpan');

    }

    public function detailWorker($id)
    {
        $kategori = Kategori::all();
        $workers = ProjectPekerjaan::where('id_project',$id)
                                    ->select('id_project','id_kategori','id_subkategori','id_vendor','status')
                                    ->groupBy('id_project','id_kategori','id_subkategori','id_vendor','status')
                                    ->get();
        $subWorker = groupSubWorker($workers);
        return view('on_progres.detail',compact('id','kategori','subWorker'));
    }

    public function subDetailWorker($id,$idProject,$subKategori)
    {
        $data = ProjectPekerjaan::where('id_project',$idProject)
                                ->where('id_kategori',$id)
                                ->where('id_subkategori',$subKategori)
                                ->get();
        return view('on_progres.detail-work',compact('data','idProject'));
    }

    public function setting($id)
    {
        return view('on_progres.setting.setting',compact('id'));
    }

    public function settingEstimasi()
    {
        return view('on_progres.setting.estimasi');
    }

    public function detailEstimasi()
    {
        return view('on_progres.setting.detail_estimasi');
    }

    public function tagihanVendor($id)
    {
        return view('on_progres.tagihan_vendor',compact('id'));
    }

    public function tagihanCustomer($id)
    {
        return view('on_progres.tagihan_customer',compact('id'));
    }

    public function vendorWorker(Request $request, $id, $project)
    {
        $idProject = $project;
        $nama_project = OnRequest::where('id',$project)->pluck('nama_project')->first();
        $nama_vendor = Vendor::where('id',$id)->pluck('name')->first();
        return view('on_progres.pekerjaan_vendor',compact('idProject','nama_project','nama_vendor','id'));
    }

    public function detailVendorWorker($id)
    {
        return view('on_progres.detail_pekerjaan_vendor',compact('id'));
    }

    public function getSubKategori($id)
    {
        $data = SubKategori::where('id_kategori',$id)->get();
        return response()->json(['status' => 200,'data' => $data]);
    }

    public function getPekerjaan($id)
    {
        $data = SettingPekerjaan::where('id_sub_kategori',$id)->with(['pekerjaan'])->get();
        return response()->json(['status' => 200,'data' => $data]);
    }

    public function ajaxPekerjaanVendor(Request $request)
    {
        $data = ProjectPekerjaan::with(['pekerjaan','projects','projects.lokasi'])
                                ->where('id_project',$request->id_project)
                                ->where('id_vendor',$request->id_vendor)
                                ->get();
        return DataTables::of($data)->addIndexColumn()->make(true);
    }

}
