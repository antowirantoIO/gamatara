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
use App\Models\RecentActivity;
use App\Models\SettingPekerjaan;
use App\Models\BeforePhoto;
use App\Models\AfterPhoto;
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
                            ->whereHas('keluhan',function($query){
                                $query->whereNotNull(['id_pm_approval','id_bod_approval']);
                            })
                            ->where('status',1)
                            ->orderBy('created_at','desc');
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
                return $data->created_at ? $data->created_at->format('d F Y') : '';
            })
            ->addColumn('end', function($data){
                return $data->target_selesai ? \Carbon\Carbon::parse($data->target_selesai)->format('d F Y') : '';
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
        $status = ProjectPekerjaan::where('id_project',$id)
                                ->where('status',1)->count();
        $projects = Keluhan::where('on_request_id',$id)
                                    ->whereNotNull(['id_pm_approval','id_bod_approval'])
                                    ->select('id_vendor')
                                    ->groupBy('id_vendor')
                                    ->get();
        $progress = ProjectPekerjaan::where('id_project',$id)
                                    ->whereNotNull('id_pekerjaan')
                                    ->select('id_vendor')
                                    ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                                    ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                                    ->groupBy('id_vendor')
                                    ->get();
        $pekerjaan = ProjectPekerjaan::where('id_project',$id)
                                    ->whereNotNull('id_pekerjaan')
                                    ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                                    ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                                    ->first();
        return view('on_progres.edit',compact('data','projects','pekerjaan','progress','status'));
    }

    public function tambahKategori(Request $request, $id,$vendor)
    {
        if($request->ajax()){
            $data = ProjectPekerjaan::where('id_project',$id)
                                    ->where('id_vendor',$vendor)
                                    ->with('kategori','subKategori')
                                    ->groupBy('id_kategori','id_subkategori','id_vendor','id_project','deskripsi_subkategori','kode_unik')
                                    ->select('id_subkategori','id_vendor','id_project','id_kategori','deskripsi_subkategori','kode_unik', DB::raw('MAX(kode_unik) as kode_unik'))
                                    ->distinct();
            $data = $data->get();

            return DataTables::of($data)->addIndexColumn()
                            ->addColumn('subkategori',function($data){
                                return strtolower($data->subKategori->name) === 'telah dilaksanakan pekerjaan' ? ($data->deskripsi_subkategori ? $data->subKategori->name . ' ' .  $data->deskripsi_subkategori : $data->subKategori->name) : $data->subKategori->name ;
                            })
                            ->addColumn('action', function($data) {
                               return ' <div class="d-flex justify-contetn-center gap-3">
                               <a href="'.route('on_progres.request-pekerjaan',[$data->id_project,$data->id_vendor,$data->id_kategori,$data->id_subkategori, $data->kode_unik ?? 0]).'" class="btn btn-info btn-sm">
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
            'id_vendor' => $request->id_vendor,
            'kode_unik' => generateBarcodeNumber()
        ]);

        return back()->with('success','Data saved successfully !');
    }

    public function addWork($id, $vendor,$kategori,$subKategori, $kodeUnik)
    {
        $works = Kategori::all();
        $vendor = Vendor::where('id',$vendor)->first();
        $pekerjaan = ProjectPekerjaan::where('id_project',$id)
                                    ->where('id_kategori',$kategori)
                                    ->where('id_subkategori',$subKategori)
                                    ->where('id_vendor',$vendor->id)
                                    ->where('kode_unik',$kodeUnik)
                                    ->get();
        $kode_unik = $pekerjaan->pluck('kode_unik')->first();
        $kategori_id = $pekerjaan->pluck('id_kategori')->first();
        $subkategori_id = $pekerjaan->pluck('id_subkategori')->first();
        $subkategori = collect();
        $settingPekerjaan = collect();
        $desc = $pekerjaan->pluck('deskripsi_subkategori')->first();
        if(!empty($kategori_id)){
            $subkategori = SubKategori::where('id_kategori',$kategori_id)->get();
        }

        $pekerjaans = Pekerjaan::all();

        return view('on_progres.request',compact('id','works','vendor','pekerjaan','kategori_id','subkategori_id','subkategori','settingPekerjaan','desc','kategori','pekerjaans','subKategori','kode_unik'));
    }


    public function requestPost(Request $request)
    {
        $validasi = Validator::make($request->all(),[
            'kategori' => 'required',
            'sub_kategori' => 'required',
            'pekerjaan' => 'required|array',
            'length' =>  'required|array',
            'width' => 'required|array',
            'thick' => 'required|array',
            'unit' => 'required|array',
            'qty' => 'required|array',
            'amount' => 'required'
        ]);

        if($validasi->fails()){
            return back()->with('error',$validasi->errors()->first());
        }
        $number = $request->kode_unik;

        foreach($request->pekerjaan as $key => $item){
            // dd($request->amount[$key]);
            $ids = $request->id[$key];
            if($ids !== null){
                $idProject = $request->id[$key];
                $currentData = RecentActivity::where('project_pekerjaan_id',$idProject)
                ->orderBy('created_at','desc')
                ->first();
                $project = ProjectPekerjaan::where('id',$idProject)->first();
                $pekerjaan = Pekerjaan::where('id',$item)->first();
                $isDifferent = false;
                if ($currentData) {
                    if ($currentData->deskripsi_pekerjaan !== $request->deskripsi[$key] ||
                        $currentData->id_lokasi !== $request->lokasi[$key] ||
                        intval($currentData->length) !== intval($request->length[$key]) ||
                        intval($currentData->width) !== intval($request->width[$key]) ||
                        intval($currentData->thick) !== intval($request->thick[$key]) ||
                        $currentData->unit !== $request->unit[$key] ||
                        intval($currentData->qty) !== intval($request->qty[$key]) ||
                        $currentData->amount != floatval($request->amount[$key]) ) {
                        $isDifferent = true;
                    }else{
                        $isDifferent = false;
                    }
                }
                ProjectPekerjaan::where('id',$idProject)->update([
                    'id_project' => $request->id_project ?? null,
                    'id_kategori' => $request->kategori ?? null,
                    'id_subkategori' => $request->sub_kategori ?? null,
                    'id_pekerjaan' => $item ?? null,
                    'id_vendor' => $request->vendor ?? null,
                    'deskripsi_subkategori' => $request->nama_pekerjaan ?? null,
                    'deskripsi_pekerjaan' => $request->deskripsi[$key] ?? null,
                    'conversion' => $request->convertion[$key] ?? null,
                    'id_lokasi' => $request->lokasi[$key] ?? null,
                    'detail' => $request->detail[$key] ?? null,
                    'length' => $request->length[$key] ?? null,
                    'width' => $request->width[$key] ?? null,
                    'thick' => $request->thick[$key] ?? null,
                    'unit' => $request->unit[$key] ?? null,
                    'qty' => $request->qty[$key] ?? null,
                    'amount' => $request->amount[$key] ?? null,
                    'harga_vendor' => $project->harga_vendor ?? $pekerjaan->harga_vendor,
                    'harga_customer' => $project->harga_customer ?? $pekerjaan->harga_customer,
                    'kode_unik' => $number
                ]);
                $recent = RecentActivity::where('project_pekerjaan_id',$request->id[$key])->first();

                if($recent){
                    if ($isDifferent === true) {
                        RecentActivity::create([
                            'project_pekerjaan_id' => $idProject,
                            'id_project' => $request->id_project,
                            'id_subkategori' => $request->sub_kategori,
                            'id_pekerjaan' => $item,
                            'id_vendor' => $request->vendor,
                            'id_kategori' => $request->kategori,
                            'deskripsi_pekerjaan' => $request->deskripsi[$key],
                            'id_lokasi' => $request->lokasi[$key],
                            'detail' => $request->detail[$key],
                            'length' => $request->length[$key],
                            'width' => $request->width[$key],
                            'thick' => $request->thick[$key],
                            'unit' => $request->unit[$key],
                            'qty' => $request->qty[$key],
                            'amount' => $request->amount[$key],
                            'description' => 'Updated Data',
                            'status' => 2,
                            'updated_at' => date('Y-m-d H:i:s'),
                            'kode_unik' => $number
                        ]);
                    }
                }else{
                    RecentActivity::create([
                        'project_pekerjaan_id' => $idProject,
                        'id_project' => $request->id_project,
                        'id_subkategori' => $request->sub_kategori,
                        'id_vendor' => $request->vendor,
                        'id_pekerjaan' => $item,
                        'id_kategori' => $request->kategori,
                        'deskripsi_pekerjaan' => $request->deskripsi[$key],
                        'id_lokasi' => $request->lokasi[$key],
                        'length' => $request->length[$key],
                        'width' => $request->width[$key],
                        'thick' => $request->thick[$key] ?? null,
                        'unit' => $request->unit[$key] ?? null,
                        'qty' => $request->qty[$key] ?? null,
                        'detail' => $request->detail[$key] ?? null,
                        'amount' => $request->amount[$key] ?? null,
                        'description' => 'Created New Data',
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'kode_unik' => $number
                    ]);
                }
            }else {
                $harga_pekerjaan = Pekerjaan::where('id',$item)->first();
                $pekerjaan = ProjectPekerjaan::create([
                    'id_project' => $request->id_project ?? null,
                    'id_kategori' => $request->kategori ?? null,
                    'id_subkategori' => $request->sub_kategori ?? null,
                    'id_pekerjaan' => $item ?? null,
                    'id_vendor' => $request->vendor ?? null,
                    'deskripsi_subkategori' => $request->nama_pekerjaan ?? null,
                    'deskripsi_pekerjaan' => $request->deskripsi[$key] ?? null,
                    'conversion' => $request->convertion[$key] ?? null,
                    'id_lokasi' => $request->lokasi[$key] ?? null,
                    'detail' => $request->detail[$key] ?? null,
                    'length' => $request->length[$key] ?? null,
                    'width' => $request->width[$key] ?? null,
                    'thick' => $request->thick[$key] ?? null,
                    'unit' => $request->unit[$key] ?? null,
                    'qty' => $request->qty[$key] ?? null,
                    'amount' => $request->amount[$key] ?? null,
                    'harga_vendor' => $harga_pekerjaan->harga_vendor ?? null,
                    'harga_customer' => $harga_pekerjaan->harga_customer ?? null,
                    'kode_unik' => $number
                ]);

                RecentActivity::create([
                    'project_pekerjaan_id' => $pekerjaan->id,
                    'id_project' => $request->id_project,
                    'id_subkategori' => $request->sub_kategori,
                    'id_pekerjaan' => $item,
                    'id_vendor' => $request->vendor,
                    'id_kategori' => $request->kategori,
                    'deskripsi_pekerjaan' => $request->deskripsi[$key] ?? null,
                    'detail' => $request->detail[$key] ?? null,
                    'id_lokasi' => $request->lokasi[$key] ?? null,
                    'length' => $request->length[$key] ?? null,
                    'width' => $request->width[$key] ?? null,
                    'thick' => $request->thick[$key] ?? null,
                    'unit' => $request->unit[$key] ?? null,
                    'qty' => $request->qty[$key] ?? null,
                    'amount' => $request->amount[$key] ?? null,
                    'harga_vendor' => $harga_pekerjaan->harga_vendor ?? null,
                    'harga_customer' => $harga_pekerjaan->harga_customer ?? null,
                    'description' => 'Created New Data',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        return back()->with('success','Data Berhasil Di Simpan');

    }

    public function deleteRequest(Request $request)
    {
        $data = ProjectPekerjaan::where('id',$request->id)->first();
        ProjectPekerjaan::where('id',$request->id)->delete();
        RecentActivity::create([
            'project_pekerjaan_id' => $data->id,
            'id_project' => $data->id_project,
            'id_pekerjaan' => $data->id_pekerjaan,
            'id_kategori' => $data->id_kategori,
            'deskripsi_pekerjaan' => $data->deskripsi,
            'id_lokasi' => $data->lokasi,
            'detail' => $data->detail,
            'length' => $data->length,
            'width' => $data->width,
            'thick' => $data->thick,
            'unit' => $data->unit,
            'qty' => $data->qty,
            'amount' => str_replace(",", ".", $data->amount),
            'harga_vendor' => str_replace(",", "", $data->harga_vendor) ,
            'harga_customer' =>  str_replace(",", "", $data->harga_customer),
            'description' => 'Deleted Data',
            'status' => 3,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);

        return response()->json(['status' => 200, 'msg' => 'Data Berhasil Di Hapus !']);

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
        $before = BeforePhoto::where('id_project',$idProject)
                            ->where('id_kategori',$id)
                            ->where('id_subkategori',$subKategori)
                            ->get();
        $after = AfterPhoto::where('id_project',$idProject)
                            ->where('id_kategori',$id)
                            ->where('id_subkategori',$subKategori)
                            ->get();
        // dd($before,$after);
        return view('on_progres.detail-work',compact('data','idProject','before','after'));
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


    public function tableData($id)
    {
        $keluhans        = Keluhan::where('on_request_id',$id)->get();

        return view('on_progres.tableData', compact('keluhans'));
    }

    public function tagihanVendor(Request $request, $id, $vendor)
    {
        $kategori = Kategori::whereHas('projectPekerjaan', function($query) use ($id) {
                                return $query->where('id_project', $id);
                            })
                            ->whereHas('projectPekerjaan',function($querys) use($vendor){
                                return $querys->where('id_vendor',$vendor);
                            })
                            ->get();

        $desiredOrder = ["UMUM", "PERAWATAN BADAN KAPAL", "KONSTRUKSI KAPAL", "PERMESINAN", "PIPA-PIPA", "INTERIOR KAPAL", "LAIN-LAIN"];

        $workers = $kategori->sortBy(function ($group, $key) use ($desiredOrder) {
            $index = array_search($key, $desiredOrder);
            return $index !== false ? $index : PHP_INT_MAX;
        });
        $subKategori = SubKategori::all();
        $lokasi = LokasiProject::all();
        return view('on_progres.tagihan_vendor',compact('id','kategori','workers','subKategori','lokasi','vendor'));
    }

    public function tagihanCustomer($id)
    {
        $kategori = Kategori::whereHas('projectPekerjaan', function($query) use ($id) {
            return $query->where('id_project', $id);
        })->get();

        $desiredOrder = ["UMUM", "PERAWATAN BADAN KAPAL", "KONSTRUKSI KAPAL", "PERMESINAN", "PIPA-PIPA", "INTERIOR KAPAL", "LAIN-LAIN"];

        $workers = $kategori->sortBy(function ($group, $key) use ($desiredOrder) {
            $index = array_search($key, $desiredOrder);
            return $index !== false ? $index : PHP_INT_MAX;
        });
        $subKategori = SubKategori::all();
        $lokasi = LokasiProject::all();
        $vendor = Vendor::all();
        return view('on_progres.tagihan_customer',compact('id','kategori','workers','subKategori','vendor'));
    }

    public function approvalProject($id)
    {
        OnRequest::where('id',$id)->update(['status' => 2]);
        return response()->json(['status' => 200,'msg' => 'success']);
    }

    public function allPekerjaanVendor(Request $request, $id, $project)
    {
        $kategori = Kategori::all();
        $workers = ProjectPekerjaan::where('id_vendor',$id)
                                    ->where('id_project',$project)
                                    ->select('id_project','id_kategori','id_subkategori','id_vendor','status','deskripsi_subkategori')
                                    ->groupBy('id_project','id_kategori','id_subkategori','id_vendor','status','deskripsi_subkategori')
                                    ->get();
        $subWorker = groupSubWorker($workers);

        $vendor = Vendor::all();
        $subKategori = SubKategori::all();
        return view('on_progres.pekerjaan_vendor.index',compact('project','kategori','subWorker','vendor','subKategori','id'));
    }

    public function vendorWorker(Request $request, $id, $project,$subkategori,$idkategori)
    {
        $idProject = $project;
        $nama_project = OnRequest::where('id',$project)->pluck('nama_project')->first();
        $nama_vendor = Vendor::where('id',$id)->pluck('name')->first();
        $pekerjaan = Pekerjaan::all();
        $lokasi = LokasiProject::all();
        return view('on_progres.pekerjaan_vendor.detail',compact('idProject','nama_project','nama_vendor','id','pekerjaan','lokasi','subkategori','idkategori'));
    }

    public function updateVendorWork(Request $request)
    {
        $validasi = Validator::make($request->all(),[
            'length' => 'required',
            'width' => 'required',
            'thick' => 'required',
            'unit' => 'required',
            'qty' => 'required',
            'amount' => 'required',
        ]);

        if($validasi->fails()){
            return back()->with('error',$validasi->errors()->first());
        }

        ProjectPekerjaan::where('id',$request->id)->update([
            'length' => $request->length,
            'width' => $request->width,
            'thick' => $request->thick,
            'unit' => $request->unit,
            'qty' => $request->qty,
            'amount' => str_replace(",", ".", $request->amount),
            'harga_vendor' => $request->harga_vendor,
            'harga_vendor' => str_replace(",", "", $request->harga_vendor),
            'harga_customer' =>  str_replace(",", "", $request->harga_customer)
        ]);

        $data = ProjectPekerjaan::where('id',$request->id)->first();

        RecentActivity::create([
            'project_pekerjaan_id' => $data->id,
            'id_vendor' => $data->id_vendor,
            'id_project' => $data->id_project,
            'id_pekerjaan' => $data->id_pekerjaan,
            'id_kategori' => $data->id_kategori,
            'id_subkategori' => $data->id_subkategori,
            'deskripsi_pekerjaan' => $data->deskripsi_pekerjaan,
            'id_lokasi' => $data->id_lokasi,
            'detail' => $data->detail,
            'length' => $data->length,
            'width' => $data->width,
            'thick' => $data->thick,
            'unit' => $data->unit,
            'qty' => $data->qty,
            'amount' => $data->amount,
            'harga_vendor' => str_replace(",", "", $request->harga_vendor) ,
            'harga_customer' =>  str_replace(",", "", $request->harga_customer),
            'description' => 'Updated Data',
            'status' => 2
        ]);

        return back()->with('success','Successfully Updated Data');
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

    public function getPekerjaan()
    {
        $data = Pekerjaan::all();
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
                                    ->where('id_subkategori',$request->id_subkategori)
                                    ->filter($request)
                                    ->orderBy('id','asc');
            if($request->has('id_pekerjaan') && !empty($request->id_pekerjaan)){
                $data->where('id_pekerjaan',$request->id_pekerjaan);
            }
            if($request->has('id_lokasi') && !empty($request->id_lokasi)){
                $data->where('id_lokasi',$request->id_lokasi);
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
                                    // ->where('id_vendor',$request->id_vendor)
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
                if (strtolower($data->subKategori->name) === 'telah dilaksanakan pekerjaan') {
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

    public function ajaxProgresPekerjaanVendor(Request $request)
    {

        if($request->ajax()){
            $data = ProjectPekerjaan::where('id_project',$request->id_project)
                                    ->where('id_kategori', $request->id_kategori)
                                    ->where('id_vendor',$request->id_vendor)
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

    public function ajaxTagihanVendor(Request $request)
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
                $data->where('id_lokasi','like','%' . $request->id_lokasi . '%');
            }

            $data = $data->get()->groupBy('id_kategori','id_subkategori')->flatten();

            return DataTables::of($data)->addIndexColumn()
            ->addColumn('subKategori', function($data) {
                if (strtolower($data->subKategori->name) === 'telah dilaksanakan pekerjaan') {
                    return $data->subKategori->name . ' ' . $data->deskripsi_subkategori;
                } else {
                    return $data->subKategori->name;
                }
            })
            ->addColumn('pekerjaan', function($data) {
                return $data->pekerjaan->name ? ($data->deskripsi_pekerjaan ? $data->pekerjaan->name . ' ' . $data->deskripsi_pekerjaan : $data->pekerjaan->name) : $data->pekerjaan->name;
            })
            ->make(true);
        }

    }

    public function ajaxTagihanCustomer(Request $request)
    {
        if($request->ajax()){
            if(!empty($request->id_kategori)){
                $data = ProjectPekerjaan::where('id_project', $request->id_project)
                                        ->where('id_kategori',$request->id_kategori)
                                        ->whereNotNull(['id_pekerjaan'])
                                        ->with(['subKategori','projects.lokasi','pekerjaan','vendors','activitys']);
            }else {
                $data = ProjectPekerjaan::where('id_project', $request->id_project)
                        ->whereNotNull(['id_pekerjaan'])
                        ->with(['subKategori','projects.lokasi','pekerjaan','vendors','activitys']);
            }

            if($request->has('sub_kategori') && !empty($request->sub_kategori)){
                $data->where('id_subkategori',$request->sub_kategori);
            }

            if($request->has('id_lokasi') && !empty($request->id_lokasi)){
                $data->where('id_lokasi',$request->id_lokasi);
            }

            $data = $data->get()->groupBy('id_kategori','id_subkategori')->flatten();

            return DataTables::of($data)->addIndexColumn()
            ->addColumn('subKategori', function($data) {
                if (strtolower($data->subKategori->name) === 'telah dilaksanakan pekerjaan') {
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

    public function ajaxUnitPekerjaan ($id)
    {
        $data = Pekerjaan::where('id',$id)->first();
        return response()->json(['data' => $data]);
    }

    public function ajaxActivityRecent(Request $request)
    {
        if($request->ajax()){
            $data = RecentActivity::where('id_project',$request->id)
                                ->where('id_kategori',$request->id_kategori)
                                ->where('id_subkategori',$request->id_subkategori)
                                ->where('id_vendor',$request->id_vendor)
                                ->where('kode_unik',$request->kode_unik)
                                ->with(['pekerjaan'])
                                ->orderBy('created_at','desc')
                                ->orderBy('updated_at','desc')
                                ->orderBy('deleted_at','desc')
                                ->get();
            return DataTables::of($data)->addIndexColumn()
            ->addColumn('harga_vendor', function($data) {
                return number_format($data->harga_vendor , 0, '.', ',');
            })
            ->addColumn('harga_customer', function($data) {
                return number_format($data->harga_customer , 0, '.', ',');
            })
            ->make(true);
        }

    }

    public function ajaxRequestDataPekerjaan(Request $request)
    {

        $data = ProjectPekerjaan::where('id_project',$request->id_project)
                    ->where('id_kategori',$request->id_kategori)
                    ->where('id_subkategori',$request->id_subkategori)
                    ->where('id_vendor',$request->id_vendor)
                    ->where('kode_unik',$request->kode_unik)
                    ->with('activitys')
                    ->get();

        if($request->ajax()){
            return DataTables::of($data)->addIndexColumn()
            ->make(true);
        }
    }

    public function editRequestPekerjaan($id)
    {
        $data = ProjectPekerjaan::where('id',$id)->with(['pekerjaan'])->first();
        return response()->json(['status' => 200,'data' => $data]);
    }

    public function updateEstimasiProject(Request $request)
    {
        OnRequest::where('id',$request->id)->update([
            'target_selesai' => $request->tanggal
        ]);

        return response()->json(['status' => 200,'msg' => 'Data Successfuly Updated']);
    }

    public function ajaxRecentActivityDetail(Request $request)
    {
        $data = RecentActivity::where('project_pekerjaan_id', $request->id)
                            ->with(['pekerjaan'])
                            ->orderBy('created_at','desc')
                            ->orderBy('updated_at','desc')
                            ->orderBy('deleted_at','desc')
                            ->get();
        return DataTables::of($data)->addIndexColumn()
        ->make(true);
    }

}
