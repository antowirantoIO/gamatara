<?php

namespace App\Http\Controllers;

use App\Exports\ExportDataOnProgress;
use App\Exports\ExportPekerjaanVendor;
use App\Models\OnRequest;
use App\Models\ProjectPekerjaan;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OnProgressExportController extends Controller
{
    public function allData(Request $request)
    {
        $data = OnRequest::where('status',1);
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
}
