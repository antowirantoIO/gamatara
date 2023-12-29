<?php

namespace App\Http\Controllers;

use App\Exports\ExportAllTagihanVendor;
use App\Exports\ExportDataOnProgress;
use App\Exports\ExportDataPekerjaan;
use App\Exports\ExportPekerjaanVendor;
use App\Exports\ExportTagihanCustomer;
use App\Models\OnRequest;
use App\Models\ProjectAdmin;
use App\Models\ProjectManager;
use App\Models\ProjectPekerjaan;
use App\Models\Vendor;
use App\Models\ViewSN;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OnProgressExportController extends Controller
{
    public function allData(Request $request)
    {
        $data = OnRequest::where('status',1);
        $cekRole = auth()->user()->role->name;
        $cekId = auth()->user()->id_karyawan;
        $cekPm = ProjectAdmin::where('id_karyawan',$cekId)->first();
        $cekPa  = ProjectManager::where('id_karyawan', $cekId)->first();
        $result = ProjectManager::get()->toArray();

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
            $data->where('code',$request->code);
        }
        if($request->has('nama_project') && !empty($request->nama_project)){
            $data->where('nama_project',$request->nama_project);
        }
        if($request->has('nama_customer') && !empty($request->nama_customer)){
            $data->where('id_customer',$request->nama_customer);
        }
        if($request->has('nama_pm') && !empty($request->nama_pm)){
            $data->where('pm_id',$request->nama_pm);
        }
        $data = $data->get();
        // return view('export.ExportDataOnProgress',compact('data'));
        return Excel::download(new ExportDataOnProgress($data),'List Data OnProgress.xlsx');
    }

    public function pekerjaanVendor(Request $request)
    {
        $data = ProjectPekerjaan::where('id_project',$request->id_project)
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
        $nama_project = OnRequest::where('id',$request->id_project)->pluck('nama_project')->first();
        $nama_vendor = Vendor::where('id',$request->id_vendor)->pluck('name')->first();
        // return view('export.ExportPekerjaanVendor',compact('data','nama_project','nama_vendor'));
        return Excel::download(new ExportPekerjaanVendor($nama_project,$nama_vendor, $data),'List Pekerjaan ' . $nama_project . ' - ' . $nama_vendor . '.xlsx');
    }

    public function dataPekerjaan(Request $request)
    {
        $data = ViewSN::where('id_project',$request->id_project)->get();
        $data = $data->groupBy(['nama_kategori','subkategori_concat']);
        $data = $data->map(function ($group) {
            return $group->sortByDesc('nama_vendor');
        });
        $project = OnRequest::where('id',$request->id_project)->first();
        return Excel::download(new ExportDataPekerjaan($data, $project),'SN-' . str_replace('/','',$project->nama_project) . '.xlsx');
        // return view('export.ExportPekerjaanOnProgress', compact('data','project'));

    }

    public function allTagihanVendor (Request $request)
    {
        $project = ProjectPekerjaan::with('vendors')
                                    ->where('id_project',$request->id_project)->get();
        $name = OnRequest::where('id',$request->id_project)->first();
        $project= $project->groupBy('vendors.name');
        return Excel::download(new ExportAllTagihanVendor($project,$request),'VENDOR BILLS-'. $name->nama_project .'.xlsx');
    }

    public function tagihanCustomer (Request $request)
    {
        $data = groupDataPekerjaanVendor($request);
        $name = OnRequest::where('id',$request->id_project)->first();
        return Excel::download(new ExportTagihanCustomer($data,$name),'CUSTOMER BILLS-'.$name->nama_project.'.xlsx');
        // return view('export.ExportTagihanCustomer',compact('data','name'));
    }
}
