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
use App\Models\Keluhan;
use App\Models\LokasiProject;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CompleteController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = OnRequest::with(['pm','pm.karyawan','customer'])->where('status',2);
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

    public function subDetailPekerjaan($id,$idProject,$subKategori,$kodeUnik)
    {
        $data = ProjectPekerjaan::where('id_project',$idProject)
                                ->where('id_kategori',$id)
                                ->where('id_subkategori',$subKategori)
                                ->whereNotNull(['id_pekerjaan'])
                                ->get();
        $before = BeforePhoto::where('id_project',$idProject)
                            ->where('kode_unik',$kodeUnik)
                            ->get();
        $after = AfterPhoto::where('id_project',$idProject)
                            ->where('kode_unik',$kodeUnik)
                            ->get();
        return view('complete.pekerjaan.detail',compact('data','idProject','before','after'));
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
                if (strtolower($data->subKategori->name) === 'telah dilaksanakan pekerjaan') {
                    return $data->subKategori->name . ' ' . $data->deskripsi_subkategori;
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

    public function ajaxSettingEstimasi(Request $request)
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
                if (strtolower($data->subKategori->name) === 'telah dilaksanakan pekerjaan') {
                    return $data->subKategori->name . ' ' . $data->deskripsi_subkategori;
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

        return view('complete.tabledata', compact('keluhan','count', 'pmAuth','keluhans'));
    }

}
