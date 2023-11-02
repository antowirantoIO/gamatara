<?php

namespace App\Http\Controllers;

use App\Exports\ExportDataComplete;
use App\Exports\ExportPekerjaanComplete;
use App\Exports\ExportPekerjaanVendor;
use App\Models\OnRequest;
use App\Models\ProjectPekerjaan;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CompleteExportController extends Controller
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
        return Excel::download(new ExportDataComplete($data),'List Data Complete.xlsx');
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
        $data = groupDataPekerjaan($request);
        $project = OnRequest::where('id',$request->id_project)->first();
        return Excel::download(new ExportPekerjaanComplete($data, $project),'List_Data_Pekerjaan_complete.xlsx');
        // header("Pragma: public");
        // header("Expires: 0");
        // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        // header("Content-Type: application/force-download");
        // header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
        // header("Content-Type: application/octet-stream");
        // header("Content-Type: application/download");
        // header('Content-Disposition: attachment; filename=List_Data_Pekerjaan.xlsx');
        return view('export.ExportPekerjaanOnProgress', compact('data','project'));

    }
}
