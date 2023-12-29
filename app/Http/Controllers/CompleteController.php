<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Karyawan;
use App\Models\OnRequest;
use App\Models\ProjectPekerjaan;
use App\Models\Kategori;
use App\Models\Vendor;
use App\Models\SubKategori;
use App\Models\Pekerjaan;
use App\Models\BeforePhoto;
use App\Models\AfterPhoto;
use App\Models\ProjectAdmin;
use App\Models\ProjectManager;
use App\Models\Keluhan;
use App\Models\LokasiProject;
use App\Models\RecentActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class CompleteController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $cekRole = auth()->user()->role->name;
            $cekId = auth()->user()->id_karyawan;
            $cekPm = ProjectAdmin::where('id_karyawan',$cekId)->first();
            $cekPa  = ProjectManager::where('id_karyawan', $cekId)->first();
            $result = ProjectManager::get()->toArray();

            $data = OnRequest::with(['pm','pm.karyawan','customer']);

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

            $data = $data->where('status',2)->get();
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
        return view('complete.index',compact('customer','pm'));
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
        return view('complete.edit',compact('data','projects','pekerjaan'));
    }

    public function detailPekerjaan($id)
    {
        $kategori = Kategori::whereHas('projectPekerjaan', function($query) use ($id) {
            return $query->where('id_project', $id);
        })->get();

        $desiredOrder = ["UMUM", "PERAWATAN BADAN KAPAL", "KONSTRUKSI KAPAL", "PERMESINAN", "PIPA-PIPA", "INTERIOR KAPAL", "LAIN-LAIN"];

        $workers = $kategori->sortBy(function ($group, $key) use ($desiredOrder) {
            $index = array_search($key, $desiredOrder);
            return $index !== false ? $index : PHP_INT_MAX;
        });
        $vendor = Vendor::all();
        $subKategori = SubKategori::all();
        return view('complete.pekerjaan.index',compact('id','kategori','vendor','subKategori','workers'));
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
                                $subKategoriName = strtolower($data->subKategori->name);
                                if (str_contains($subKategoriName, 'telah dilaksanakan pekerjaan')) {
                                    return $data->deskripsi_subkategori
                                        ? $data->subKategori->name . ' ' . strtoupper($data->deskripsi_subkategori)
                                        : $data->subKategori->name . ' ' . '';
                                } else {
                                    return $data->subKategori->name;
                                }
                            })
                            ->addColumn('action', function($data) {
                               return ' <div class="d-flex justify-contetn-center gap-3">
                               <a href="'.route('complete.request-pekerjaan',[$data->id_project,$data->id_vendor,$data->id_kategori,$data->id_subkategori, $data->kode_unik ?? 0]).'" class="btn btn-info btn-sm">
                                   <span>
                                       <i><img src="'.asset('assets/images/edit.svg').'" style="width: 15px;"></i>
                                   </span>
                               </a>
                           </div>';
                            })
                            ->make(true);
        }
        $categories = Kategori::all();
        return view('complete.tagihan.tambah_kategori',compact('id','vendor','categories'));
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

        return view('complete.request',compact('id','works','vendor','pekerjaan','kategori_id','subkategori_id','subkategori','settingPekerjaan','desc','kategori','pekerjaans','subKategori','kode_unik'));
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
                            'harga_vendor' => $project->harga_vendor ?? $pekerjaan->harga_vendor,
                            'harga_customer' => $project->harga_customer ?? $pekerjaan->harga_customer,
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
                        'harga_vendor' => $project->harga_vendor ?? $pekerjaan->harga_vendor,
                        'harga_customer' => $project->harga_customer ?? $pekerjaan->harga_customer,
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

        return back()->with('success','Data Successfuly Saved');

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

    public function subDetailPekerjaan(Request $request,$id,$idProject,$subKategori,$kodeUnik)
    {
        $data = ProjectPekerjaan::where('id_project',$idProject)
                                    ->where('id_kategori',$id)
                                    ->where('id_subkategori',$subKategori)
                                    ->whereNotNull(['id_pekerjaan'])
                                    ->with('vendors','subKategori')
                                    ->get();
        if($request->ajax()){
            $data = ProjectPekerjaan::where('id_project',$idProject)
                                    ->where('id_kategori',$id)
                                    ->where('id_subkategori',$subKategori)
                                    ->whereNotNull(['id_pekerjaan'])
                                    ->with('vendors','subKategori','pekerjaan')
                                    ->get();
            return DataTables::of($data)->addIndexColumn()
            ->addColumn('length', function($data){
                return $data->length ?  number_format($data->length,2, '.','') : 0 ;
            })
            ->addColumn('width', function($data){
                return $data->width ?  number_format($data->width,2, '.','') : 0 ;
            })
            ->addColumn('thick', function($data){
                return $data->thick ?  number_format($data->thick,2, '.','') : 0 ;
            })
            ->addColumn('qty', function($data){
                return $data->qty ?  number_format($data->qty,2, '.','') : 0 ;
            })
            ->addColumn('amount', function($data){
                return $data->amount ?  number_format($data->amount,2, '.','') : 0 ;
            })
            ->addColumn('vendor', function($data){
                return $data->vendors->name;
            })
            ->make(true);

        }
        $before = BeforePhoto::where('id_project',$idProject)
                            ->where('kode_unik',$kodeUnik)
                            ->get();
        // dd($before);
        $after = AfterPhoto::where('id_project',$idProject)
                            ->where('kode_unik',$kodeUnik)
                            ->get();
        return view('complete.pekerjaan.detail',compact('idProject','before','after','id','subKategori','kodeUnik'));
    }

    public function dataTagihan(Request $request, $id)
    {
        $kategori = Kategori::all();
        $allData = ProjectPekerjaan::where('id_project', $id)->get();
        $workers = $allData->groupBy('id_kategori','id_subkategori');
        $subKategori = SubKategori::all();
        $lokasi = LokasiProject::all();
        return view('complete.tagihan.index',compact('id','kategori','workers','subKategori','lokasi'));
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
        return view('complete.tagihan.customer',compact('id','kategori','workers','subKategori','vendor'));
    }

    public function tagihanVendor(Request $request, $id, $vendor)
    {
        $kategori = Kategori::whereHas('projectPekerjaan', function($query) use ($id,$vendor) {
            return $query->where('id_project', $id)->where('id_vendor',$vendor);
        })
        ->get();
        $desiredOrder = ["UMUM", "PERAWATAN BADAN KAPAL", "KONSTRUKSI KAPAL", "PERMESINAN", "PIPA-PIPA", "INTERIOR KAPAL", "LAIN-LAIN"];

        $workers = $kategori->sortBy(function ($group, $key) use ($desiredOrder) {
        $index = array_search($key, $desiredOrder);
        return $index !== false ? $index : PHP_INT_MAX;
        });
        $subKategori = SubKategori::all();
        $lokasi = LokasiProject::all();
        return view('complete.tagihan.vendor',compact('id','kategori','workers','subKategori','lokasi','vendor'));
    }

    public function allPekerjaanVendor(Request $request, $id, $project)
    {
        $kategori = Kategori::whereHas('projectPekerjaan', function($query) use ($project,$id) {
            return $query->where('id_project', $project)->where('id_vendor',$id);
        })
        ->get();
        $desiredOrder = ["UMUM", "PERAWATAN BADAN KAPAL", "KONSTRUKSI KAPAL", "PERMESINAN", "PIPA-PIPA", "INTERIOR KAPAL", "LAIN-LAIN"];

        $workers = $kategori->sortBy(function ($group, $key) use ($desiredOrder) {
        $index = array_search($key, $desiredOrder);
        return $index !== false ? $index : PHP_INT_MAX;
        });

        $vendor = Vendor::all();
        $subKategori = SubKategori::all();
        return view('complete.pekerjaan_vendor.index',compact('project','kategori','vendor','subKategori','id','workers'));
    }

    public function pekerjaanVendor(Request $request, $id, $project,$subkategori,$idkategori)
    {
        $idProject = $project;
        $nama_project = OnRequest::where('id',$project)->pluck('nama_project')->first();
        $nama_vendor = Vendor::where('id',$id)->pluck('name')->first();
        $pekerjaan = Pekerjaan::all();
        $lokasi = LokasiProject::all();
        return view('complete.pekerjaan_vendor.detail',compact('idProject','nama_project','nama_vendor','id','pekerjaan','lokasi','subkategori','idkategori'));
    }

    public function setting($id)
    {
        return view('complete.setting.index',compact('id'));
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
        return view('complete.setting.estimasi', compact('kategori','subWorker','id','vendor','subKategori'));
    }

    public function detailEstimasi($id,$idProject)
    {
        $data = ProjectPekerjaan::where('id_kategori',$id)->get();
        $kategori = Kategori::where('id',$id)->first();
        return view('complete.setting.detail_estimasi',compact('idProject','data','kategori'));
    }

    public function ajaxProgresPekerjaan(Request $request)
    {
        if($request->ajax()){
            DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
            if(!empty($request->id_kategori)){
                $data = ProjectPekerjaan::where('id_project', $request->id_project)
                                        ->where('id_kategori',$request->id_kategori)
                                        ->whereNotNull(['id_pekerjaan'])
                                        ->groupBy('id_subkategori','deskripsi_subkategori')
                                        ->with(['subKategori','projects.lokasi','pekerjaan','vendors','activitys']);
            }else {
                $data = ProjectPekerjaan::where('id_project', $request->id_project)
                                        ->whereNotNull(['id_pekerjaan'])
                                        ->groupBy('id_subkategori','deskripsi_subkategori')
                                        ->with(['subKategori','projects.lokasi','pekerjaan','vendors','activitys']);
            }

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
                $subKategoriName = strtolower($data->subKategori->name);
                if (str_contains($subKategoriName, 'telah dilaksanakan pekerjaan')) {
                    return $data->deskripsi_subkategori
                        ? $data->subKategori->name . ' ' . strtoupper($data->deskripsi_subkategori)
                        : $data->subKategori->name . ' ' . '';
                } else {
                    return $data->subKategori->name;
                }
            })
            ->addColumn('progres', function($data){
                return getProgress($data->id_project,$data->id_kategori,$data->id_vendor,3) . ' / ' . getProgress($data->id_project,$data->id_kategori,$data->id_vendor,null);
            })
            ->make(true);
        }
    }

    public function ajaxPekerjaanVendor(Request $request)
    {
        if($request->ajax()){
            $data = ProjectPekerjaan::with(['pekerjaan','projects','lokasi'])
                                    ->where('id_project',$request->id_project)
                                    ->where('id_vendor',$request->id_vendor);

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

    // public function ajaxSettingEstimasi(Request $request)
    // {
    //     if($request->ajax()){
    //         $data = ProjectPekerjaan::where('id_project',$request->id_project)
    //                                 ->where('id_kategori', $request->id_kategori)
    //                                 ->with(['subKategori', 'vendors'])
    //                                 ->groupBy('id_kategori','id_subkategori','id_vendor','id_project','deskripsi_subkategori')
    //                                 ->select('id_subkategori','id_vendor','id_project','id_kategori','deskripsi_subkategori', DB::raw('MAX(id) as id'))
    //                                 ->distinct();

    //         if($request->has('sub_kategori') && !empty($request->sub_kategori)){
    //             $data->where('id_subkategori',$request->sub_kategori);
    //         }

    //         if($request->has('nama_vendor') && !empty($request->nama_vendor)){
    //             $vendor = $request->nama_vendor;
    //             $data->whereHas('vendors',function($item) use(&$vendor){
    //                 $item->where('id',$vendor);
    //             });
    //         }


    //         $data = $data->get();

    //         return DataTables::of($data)->addIndexColumn()
    //         ->addColumn('pekerjaan', function($data) {
    //             if ($data->subKategori->name === 'Telah dilaksanakan pekerjaan') {
    //                 return $data->subKategori->name . ' ' . $data->deskripsi_subkategori;
    //             } else {
    //                 return $data->subKategori->name;
    //             }
    //         })
    //         ->addColumn('progres', function($data){
    //             $progres = getProgress($data->id_project,$data->id_kategori,$data->id_vendor);
    //             return $progres->total_status_2 . ' / ' . $progres->total_status_1;
    //         })
    //         ->make(true);
    //     }
    // }

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
            ->make(true);
        }

    }

    public function ajaxTagihanVendor(Request $request)
    {
        if($request->ajax()){
            $data = ProjectPekerjaan::where('id_project', $request->id_project)
                                    ->where('id_kategori',$request->id_kategori)
                                    ->where('id_vendor',$request->id_vendor)
                                    ->whereNotNull(['id_pekerjaan'])
                                    ->with(['subKategori','projects.lokasi','pekerjaan','activitys']);

            if($request->has('sub_kategori') && !empty($request->sub_kategori)){
                $data->where('id_subkategori',$request->sub_kategori);
            }

            if($request->has('id_lokasi') && !empty($request->id_lokasi)){
                $data->where('id_lokasi','like','%' . $request->id_lokasi . '%');
            }

            $data = $data->get()->groupBy('id_kategori','id_subkategori')->flatten();

            return DataTables::of($data)->addIndexColumn()
            ->addColumn('subKategori', function($data) {
                $subKategoriName = strtolower($data->subKategori->name);
                if (str_contains($subKategoriName, 'telah dilaksanakan pekerjaan')) {
                    return $data->deskripsi_subkategori
                        ? $data->subKategori->name . ' ' . strtoupper($data->deskripsi_subkategori)
                        : $data->subKategori->name . ' ' . '';
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
                $subKategoriName = strtolower($data->subKategori->name);
                if (str_contains($subKategoriName, 'telah dilaksanakan pekerjaan')) {
                    return $data->deskripsi_subkategori
                        ? $data->subKategori->name . ' ' . strtoupper($data->deskripsi_subkategori)
                        : $data->subKategori->name . ' ' . '';
                } else {
                    return $data->subKategori->name;
                }
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
                $subKategoriName = strtolower($data->subKategori->name);
                if (str_contains($subKategoriName, 'telah dilaksanakan pekerjaan')) {
                    return $data->deskripsi_subkategori
                        ? $data->subKategori->name . ' ' . strtoupper($data->deskripsi_subkategori)
                        : $data->subKategori->name . ' ' . '';
                } else {
                    return $data->subKategori->name;
                }
            })
            ->addColumn('progres', function($data){
                return getProgress($data->id_project,$data->id_kategori,$data->id_vendor,$data->id_subkategori,3). ' / ' . getProgress($data->id_project,$data->id_kategori,$data->id_vendor,$data->id_subkategori,null);
            })
            ->make(true);
        }
    }

    public function tableData($id)
    {
        $pmAuth         = Auth::user()->role->name ?? '';
        $keluhans        = Keluhan::where('on_request_id',$id)->get();
        $count          = $keluhans->whereNotNull('id_pm_approval')->whereNotNull('id_bod_approval')->count();
        $keluhan        = count($keluhans);
        return view('complete.tabledata', compact('keluhan','count', 'pmAuth','keluhans','id'));
    }

}
