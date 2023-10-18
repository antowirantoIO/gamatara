<?php

namespace App\Http\Controllers;

use App\Models\OnRequest;
use App\Models\ProjectPekerjaan;
use App\Models\Kategori;
use App\Models\Vendor;
use App\Models\SubKategori;
use App\Models\Pekerjaan;
use App\Models\LokasiProject;

use Illuminate\Http\Request;

class CompleteController extends Controller
{
    public function index()
    {
        $data = OnRequest::all();
        return view('complete.index',compact('data'));
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
        $kategori = Kategori::all();
        $workers = ProjectPekerjaan::where('id_project',$id)
                                    ->select('id_project','id_kategori','id_subkategori','id_vendor','status','deskripsi_subkategori')
                                    ->groupBy('id_project','id_kategori','id_subkategori','id_vendor','status','deskripsi_subkategori')
                                    ->get();
        $subWorker = groupSubWorker($workers);
        $vendor = Vendor::all();
        $subKategori = SubKategori::all();
        return view('complete.pekerjaan.index',compact('kategori','id','vendor','subKategori','subWorker'));
    }

    public function subDetailPekerjaan($id,$idProject,$subKategori)
    {
        $data = ProjectPekerjaan::where('id_project',$idProject)
                                ->where('id_kategori',$id)
                                ->where('id_subkategori',$subKategori)
                                ->get();
        return view('complete.pekerjaan.detail',compact('data','idProject'));
    }

    public function tagihanCustomer($id)
    {
        $kategori = Kategori::all();
        $allData = ProjectPekerjaan::where('id_project', $id)->get();
        $workers = $allData->groupBy('id_kategori','id_subkategori');
        return view('complete.tagihan.customer',compact('id','kategori','workers'));
    }

    public function tagihanVendor($id)
    {
        $kategori = Kategori::all();
        $allData = ProjectPekerjaan::where('id_project', $id)->get();
        $workers = $allData->groupBy('id_kategori','id_subkategori');
        return view('complete.tagihan.vendor',compact('id','kategori','workers'));
    }

    public function pekerjaanVendor(Request $request, $id, $project)
    {
        $idProject = $project;
        $nama_project = OnRequest::where('id',$project)->pluck('nama_project')->first();
        $nama_vendor = Vendor::where('id',$id)->pluck('name')->first();
        $pekerjaan = Pekerjaan::all();
        $lokasi = LokasiProject::all();
        return view('complete.pekerjaan_vendor.detail',compact('idProject','nama_project','nama_vendor','id','pekerjaan','lokasi'));
    }
}
