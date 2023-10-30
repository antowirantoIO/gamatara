<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Karyawan;
use App\Models\OnRequest;
use App\Models\Pekerjaan;
use App\Models\SubKategori;
use App\Models\Kategori;
use App\Models\Keluhan;
use App\Models\LokasiProject;
use App\Models\ProjectPekerjaan;
use App\Models\SettingPekerjaan;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class OnProgressController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = OnRequest::with(['pm','pm.karyawan','customer'])
                            ->where('status',1);
            if($request->has('code') && !empty($request->code)){
                $data->where('code','like','%'.$request->code.'%');
            }

            if($request->has('nama_project') && !empty($request->nama_project)){
                $data->where('nama_project','like', '%'.$request->nama_project.'%');
            }
            if($request->has('nama_customer') && !empty($request->nama_customer)){
                $data->where('id_customer',$request->nama_customer);
            }
            if($request->has('nama_pm') && !empty($request->nama_pm)){
                $data->where('pm_id',$request->nama_pm);
            }

            if ($request->has('date') && !empty($request->date)) {
                $dates = explode(' - ', $request->date);
                $start = $dates[0];
                $end = $dates[1];
                $data->whereDate('start_project', '>=', $start);
                $data->whereDate('target_selesai', '<=', $end);
            }

            $data = $data->get();
            return DataTables::of($data)->addIndexColumn()
            ->addColumn('progres', function($data){
                return getProgresProject($data->id) . ' / ' . getCompleteProject($data->id);
            })
            ->addColumn('start', function($data){
                return $data->tanggal_mulai ? $data->tanggal_mulai->format('d-m-Y H:i') : '';
            })
            ->addColumn('end', function($data){
                return $data->actual_selesai ? $data->actual_selesai->format('d-m-Y H:i') : '';
            })
            ->make(true);
        }
        $customer   = Customer::get();
        $pm = Karyawan::all();
        return view('on_progres.index',compact('customer','pm'));
    }

    public function edit($id)
    {
        $data = OnRequest::find($id);
        $projects = Keluhan::where('on_request_id',$id)
                                    // ->where('id_pm_approval','!=',null)
                                    ->select('id_vendor')
                                    // ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                                    // ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                                    ->groupBy('id_vendor')
                                    ->get();
        $progress = ProjectPekerjaan::where('id_project',$id)
                                    ->select('id_vendor')
                                    ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                                    ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                                    ->groupBy('id_vendor')
                                    ->get();
        $pekerjaan = ProjectPekerjaan::where('id_project',$id)
                                    ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                                    ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                                    ->first();
        return view('on_progres.edit',compact('data','projects','pekerjaan','progress'));
    }

    public function tambahKategori(Request $request, $id,$vendor)
    {
        if($request->ajax()){
            $data = ProjectPekerjaan::where('id_project',$id)
                                    ->where('id_vendor',$vendor)
                                    ->with('kategori','subKategori')
                                    ->groupBy('id_kategori','id_subkategori','id_vendor','id_project','deskripsi_subkategori')
                                    ->select('id_subkategori','id_vendor','id_project','id_kategori','deskripsi_subkategori', DB::raw('MAX(id) as id'))
                                    ->distinct();
            $data = $data->get();
            return DataTables::of($data)->addIndexColumn()
                            ->addColumn('action', function($data) {
                               return ' <div class="d-flex justify-contetn-center gap-3">
                               <a href="'.route('on_progres.request-pekerjaan',[$data->id_project,$data->id_vendor]).'" class="btn btn-info btn-sm">
                                   <span>
                                       <i><img src="'.asset('assets/images/edit.svg').'" style="width: 15px;"></i>
                                   </span>
                               </a>
                           </div>';
                            })
                            ->make(true);
        }
        $categories = Kategori::all();
        return view('on_progres.tagihan.tambah_kategori',compact('id','vendor','categories'));
    }

    public function storeTambahKategori(Request $request)
    {
        $validation = Validator::make($request->all(),[
            'id_vendor' => 'required',
            'id_project' => 'required',
            'kategori' => 'required',
            'subkategori' => 'required'
        ]);

        if($validation->fails()){
            return back()->with('error',$validation->errors()->first());
        }

        ProjectPekerjaan::create([
            'id_project' => $request->id_project,
            'id_kategori' => $request->kategori,
            'id_subkategori' => $request->subkategori,
            'id_vendor' => $request->id_vendor
        ]);

        return back()->with('success','Data Berhasil Di Simpan !');
    }

    public function addWork($id, $vendor)
    {
        $works = Kategori::all();
        $vendor = Vendor::where('id',$vendor)->first();
        $pekerjaan = ProjectPekerjaan::where('id_project',$id)->where('id_vendor',$vendor->id)->get();
        $kategori_id = $pekerjaan->pluck('id_kategori')->first();
        $subkategori_id = $pekerjaan->pluck('id_subkategori')->first();
        $subkategori = collect();
        $settingPekerjaan = collect();
        $desc = $pekerjaan->pluck('deskripsi_subkategori')->first();
        if(!empty($kategori_id)){
            $subkategori = SubKategori::where('id_kategori',$kategori_id)->get();
        }
        if(!empty($subkategori_id)){
            $settingPekerjaan = SettingPekerjaan::where('id_sub_kategori',$subkategori_id)->get();
        }

        return view('on_progres.request',compact('id','works','vendor','pekerjaan','kategori_id','subkategori_id','subkategori','settingPekerjaan','desc'));
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
            $ids = $request->id[$key];
            if($ids !== null){
                $idProject = $request->id[$key];
                ProjectPekerjaan::where('id',$idProject)->update([
                    'id_project' => $request->id_project,
                    'id_kategori' => $request->kategori,
                    'id_subkategori' => $request->sub_kategori,
                    'id_pekerjaan' => $item,
                    'id_vendor' => $request->vendor,
                    'deskripsi_subkategori' => $request->nama_pekerjaan,
                    'deskripsi_pekerjaan' => $request->deskripsi[$key],
                    'id_lokasi' => $request->lokasi[$key],
                    'detail' => $request->detail[$key],
                    'length' => $request->length[$key],
                    'width' => $request->width[$key],
                    'thick' => $request->thick[$key],
                    'unit' => $request->unit[$key],
                    'qty' => $request->qty[$key],
                    'amount' => $request->amount[$key],
                ]);
            }else {
                ProjectPekerjaan::create([
                    'id_project' => $request->id_project,
                    'id_kategori' => $request->kategori,
                    'id_subkategori' => $request->sub_kategori,
                    'id_pekerjaan' => $item,
                    'id_vendor' => $request->vendor,
                    'deskripsi_subkategori' => $request->nama_pekerjaan,
                    'deskripsi_pekerjaan' => $request->deskripsi[$key],
                    'id_lokasi' => $request->lokasi[$key],
                    'detail' => $request->detail[$key],
                    'length' => $request->length[$key],
                    'width' => $request->width[$key],
                    'thick' => $request->thick[$key],
                    'unit' => $request->unit[$key],
                    'qty' => $request->qty[$key],
                    'amount' => $request->amount[$key],
                ]);
            }
        }

        return back()->with('success','Data Berhasil Di Simpan');

    }

    public function detailWorker($id)
    {
        $kategori = Kategori::all();
        $workers = ProjectPekerjaan::where('id_project',$id)
                                    ->select('id_project','id_kategori','id_subkategori','id_vendor','status','deskripsi_subkategori')
                                    ->groupBy('id_project','id_kategori','id_subkategori','id_vendor','status','deskripsi_subkategori')
                                    ->get();
        $subWorker = groupSubWorker($workers);
        $vendor = Vendor::all();
        $subKategori = SubKategori::all();
        return view('on_progres.detail',compact('id','kategori','subWorker','vendor','subKategori'));
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

    public function settingEstimasi($id)
    {
        $kategori = Kategori::all();
        $workers = ProjectPekerjaan::where('id_project',$id)
        ->select('id_project','id_kategori','id_subkategori','id_vendor','status','deskripsi_subkategori')
        ->groupBy('id_project','id_kategori','id_subkategori','id_vendor','status','deskripsi_subkategori')
        ->get();
        $subWorker = groupSubWorker($workers);
        $vendor = Vendor::all();
        $subKategori = SubKategori::all();
        return view('on_progres.setting.estimasi', compact('kategori','subWorker','id','vendor','subKategori'));
    }

    public function detailEstimasi($id,$idProject)
    {
        $data = ProjectPekerjaan::where('id_kategori',$id)->get();
        return view('on_progres.setting.detail_estimasi',compact('idProject','data'));
    }

    public function dataTagihan(Request $request, $id)
    {
        $kategori = Kategori::all();
        $allData = ProjectPekerjaan::where('id_project', $id)->get();
        $workers = $allData->groupBy('id_kategori','id_subkategori');
        $subKategori = SubKategori::all();
        $lokasi = LokasiProject::all();
        return view('on_progres.tagihan.index',compact('id','kategori','workers','subKategori','lokasi'));
    }

    public function tagihanVendor(Request $request, $id,$vendor)
    {
        $kategori = Kategori::all();
        $subKategori = SubKategori::all();
        $lokasi = LokasiProject::all();
        $allData = ProjectPekerjaan::where('id_project', $id)
                                    ->where('id_vendor',$vendor)
                                    ->get();
        $workers = $allData->groupBy('id_kategori','id_subkategori');
        return view('on_progres.tagihan_vendor',compact('id','kategori','workers','subKategori','lokasi','vendor'));
    }

    public function tagihanCustomer($id)
    {
        $kategori = Kategori::all();
        $allData = ProjectPekerjaan::where('id_project', $id)->get();
        $workers = $allData->groupBy('id_kategori','id_subkategori');
        $subKategori = SubKategori::all();
        $lokasi = LokasiProject::all();
        return view('on_progres.tagihan_customer',compact('id','kategori','workers','subKategori','lokasi'));
    }

    public function vendorWorker(Request $request, $id, $project)
    {
        $idProject = $project;
        $nama_project = OnRequest::where('id',$project)->pluck('nama_project')->first();
        $nama_vendor = Vendor::where('id',$id)->pluck('name')->first();
        $pekerjaan = Pekerjaan::all();
        $lokasi = LokasiProject::all();
        return view('on_progres.pekerjaan_vendor',compact('idProject','nama_project','nama_vendor','id','pekerjaan','lokasi'));
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

    public function getLokasi()
    {
        $lokasi = LokasiProject::all();
        return response()->json(['status' => 200,'data' => $lokasi]);
    }

    public function ajaxPekerjaanVendor(Request $request)
    {
        if($request->ajax()){
            $data = ProjectPekerjaan::with(['pekerjaan','projects','lokasi'])
                                    ->where('id_project',$request->id_project)
                                    ->where('id_vendor',$request->id_vendor)
                                    ->orderBy('id','desc');
            if($request->has('id_pekerjaan') && !empty($request->id_pekerjaan)){
                $data->where('id_pekerjaan',$request->id_pekerjaan);
            }
            if($request->has('id_lokasi') && !empty($request->id_lokasi)){
                $lokasi = $request->id_lokasi;
                $data->whereHas('projects',function ($query) use(&$lokasi){
                    $query->whereHas('lokasi',function($querys) use(&$lokasi){
                        $querys->where('id_lokasi_project',$lokasi);
                    });
                });
            }

            $data = $data->get();
            return DataTables::of($data)->addIndexColumn()->make(true);
        }
    }

    public function ajaxProgresPekerjaan(Request $request)
    {

        if($request->ajax()){
            $data = ProjectPekerjaan::where('id_project',$request->id_project)
                                    ->where('id_kategori', $request->id_kategori)
                                    ->with(['subKategori', 'vendors'])
                                    ->groupBy('id_kategori','id_subkategori','id_vendor','id_project','deskripsi_subkategori')
                                    ->select('id_subkategori','id_vendor','id_project','id_kategori','deskripsi_subkategori', DB::raw('MAX(id) as id'))
                                    ->distinct();

            if($request->has('sub_kategori') && !empty($request->sub_kategori)){
                $data->where('id_subkategori',$request->sub_kategori);
            }

            if($request->has('nama_vendor') && !empty($request->nama_vendor)){
                $vendor = $request->nama_vendor;
                $data->whereHas('vendors',function($item) use(&$vendor){
                    $item->where('id',$vendor);
                });
            }


            $data = $data->get();

            return DataTables::of($data)->addIndexColumn()
            ->addColumn('pekerjaan', function($data) {
                if ($data->subKategori->name === 'Telah dilaksanakan pekerjaan') {
                    return $data->subKategori->name . ' ' . $data->deskripsi_subkategori;
                } else {
                    return $data->subKategori->name;
                }
            })
            ->addColumn('progres', function($data){
                $progres = getProgress($data->id_project,$data->id_kategori,$data->id_vendor);
                return $progres->total_status_2 . ' / ' . $progres->total_status_1;
            })
            ->make(true);
        }
    }

    public function ajaxSettingEstimasi(Request $request)
    {
        if($request->ajax()){
            $data = ProjectPekerjaan::where('id_project',$request->id_project)
                                    ->where('id_kategori', $request->id_kategori)
                                    ->with(['subKategori', 'vendors'])
                                    ->groupBy('id_kategori','id_subkategori','id_vendor','id_project','deskripsi_subkategori')
                                    ->select('id_subkategori','id_vendor','id_project','id_kategori','deskripsi_subkategori', DB::raw('MAX(id) as id'))
                                    ->distinct();

            if($request->has('nama_customer') && !empty($request->nama_customer)){
                $data->where('id_subkategori',$request->id_subkategori);
                $data->where('id_pekerjaan',$request->nama_customer);
            }

            $data = $data->get();

            return DataTables::of($data)->addIndexColumn()
            ->addColumn('pekerjaan', function($data) {
                if ($data->subKategori->name === 'Telah dilaksanakan pekerjaan') {
                    return $data->subKategori->name . ' ' . $data->deskripsi_subkategori;
                } else {
                    return $data->subKategori->name;
                }
            })
            ->addColumn('progres', function($data){
                $progres = getProgress($data->id_project,$data->id_kategori,$data->id_vendor);
                return $progres->total_status_2 . ' / ' . $progres->total_status_1;
            })
            ->make(true);
        }
    }

    public function ajaxTagihan(Request $request)
    {
        if($request->ajax()){
            $data = ProjectPekerjaan::where('id_project', $request->id_project)
                                    ->where('id_kategori',$request->id_kategori)
                                    ->where('id_vendor',$request->id_vendor)
                                    ->with(['subKategori','projects.lokasi','pekerjaan']);

            if($request->has('sub_kategori') && !empty($request->sub_kategori)){
                $data->where('id_subkategori',$request->sub_kategori);
            }

            if($request->has('id_lokasi') && !empty($request->id_lokasi)){
                $data->where('id_lokasi',$request->id_lokasi);
            }

            $data = $data->get()->groupBy('id_kategori','id_subkategori')->flatten();

            return DataTables::of($data)->addIndexColumn()
            ->addColumn('subKategori', function($data) {
                if ($data->subKategori->name === 'Telah dilaksanakan pekerjaan') {
                    return $data->subKategori->name . ' ' . $data->deskripsi_subkategori;
                } else {
                    return $data->subKategori->name;
                }
            })
            ->make(true);
        }

    }

    public function ajaxAllTagihan (Request $request)
    {
        $data = ProjectPekerjaan::where('id_project', $request->id)
                                ->with(['subKategori','projects','pekerjaan','projects.pm','projects.customer','vendors'])
                                ->groupBy('id_kategori','id_subkategori','id_vendor','id_project','deskripsi_subkategori')
                                ->select('id_subkategori','id_vendor','id_project','id_kategori','deskripsi_subkategori', DB::raw('MAX(id) as id'))
                                ->distinct();
        if($request->ajax()){
            $data = $data->get()->groupBy('id_kategori','id_subkategori')->flatten();
            return DataTables::of($data)->addIndexColumn()
            ->addColumn('subKategori', function($data) {
                if ($data->subKategori->name === 'Telah dilaksanakan pekerjaan') {
                    return $data->subKategori->name . ' ' . $data->deskripsi_subkategori;
                } else {
                    return $data->subKategori->name;
                }
            })
            ->make(true);
        }

    }

}
