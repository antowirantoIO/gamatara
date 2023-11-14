<?php

use App\Models\Kategori;
use App\Models\ProjectPekerjaan;
use Carbon\Carbon;


if (!function_exists('set_active')) {
    function set_active($url, $output = 'active')
    {
        if (is_array($url)) {
            foreach ($url as $u) {
                if (Route::is($u)) {
                    return $output;
                }
            }
        } else {
            if (Route::is($url)) {
                return $output;
            }
        }
    }
}

function groupSubWorker($data)
{
    $datas = collect();
    $data->each(function($item) use(&$datas){
        $id_kategori = $item->id_kategori;
        if (!$datas->has($id_kategori)) {
            $datas->put($id_kategori, collect([$item]));
        } else {
            $datas[$id_kategori]->push($item);
        }
    });
    return $datas;
}

function getNameKategori($id)
{
    $data = Kategori::where('id',$id)->pluck('name')->first();
    return ucwords(strtolower($data));
}

function getProgress($id, $idKategori,$idvendor)
{
    $pekerjaan = ProjectPekerjaan::where('id_project',$id)
                                ->where('id_kategori',$idKategori)
                                ->where('id_vendor',$idvendor)
                                ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                                ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                                ->first();
    return $pekerjaan;
}

function getCompleteProject($id)
{
    $pekerjaan = ProjectPekerjaan::where('id_project',$id)
                                ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                                ->first();
    if($pekerjaan->total_status_1){
        return $pekerjaan->total_status_1;
    }else{
        return 0;
    }
}

function getProgresProject($id)
{
    $pekerjaan = ProjectPekerjaan::where('id_project',$id)
                                ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                                ->first();
    if($pekerjaan->total_status_2){
        return $pekerjaan->total_status_2;
    }else{
        return 0;
    }
}

function groupDataPekerjaan($request)
{
    $customOrder = ["UMUM", "PERAWATAN BADAN KAPAL", "KONSTRUKSI KAPAL","PERMESINAN","PIPA-PIPA","INTERIOR KAPAL","LAIN-LAIN"];
    $kategori = Kategori::orderByRaw("FIELD(name, '" . implode("','", $customOrder) . "')")->get();
    $data = collect();
    $kategori->each(function($item) use ($data,&$request){
        $datas = ProjectPekerjaan::where('id_kategori',$item->id)->where('id_project',$request->id_project);

        if($request->has('sub_kategori') && !empty($request->sub_kategori)){
            $datas->where('id_subkategori',$request->id_subkategori);
        }

        if($request->has('nama_vendor') && !empty($request->nama_vendor)){
            $datas->where('id_vendor',$request->nama_vendor);
        }
        $datas = $datas->get();
        $data[$item->name] = $datas;
    });
    return $data;
}

function groupDataPekerjaanVendor($request)
{
    $customOrder = ["UMUM", "PERAWATAN BADAN KAPAL", "KONSTRUKSI KAPAL","PERMESINAN","PIPA-PIPA","INTERIOR KAPAL","LAIN-LAIN"];
    $kategori = Kategori::orderByRaw("FIELD(name, '" . implode("','", $customOrder) . "')")->get();
    $data = collect();
    $kategori->each(function($item) use ($data,&$request){
        $datas = ProjectPekerjaan::where('id_kategori',$item->id)
                                // ->where('id_vendor',1)
                                ->where('id_project',$request->id_project);

        if($request->has('sub_kategori') && !empty($request->sub_kategori)){
            $datas->where('id_subkategori',$request->id_subkategori);
        }

        if($request->has('nama_vendor') && !empty($request->nama_vendor)){
            $datas->where('id_vendor',$request->nama_vendor);
        }
        $datas = $datas->get();
        if ($datas->isNotEmpty()) {
            $data[$item->name] = $datas;
        }
    });
    return $data;
}

function groupExportTagihanVendor($request,$id_vendor)
{
    $customOrder = ["UMUM", "PERAWATAN BADAN KAPAL", "KONSTRUKSI KAPAL","PERMESINAN","PIPA-PIPA","INTERIOR KAPAL","LAIN-LAIN"];
    $kategori = Kategori::orderByRaw("FIELD(name, '" . implode("','", $customOrder) . "')")->get();
    $data = collect();
    $kategori->each(function($item) use ($data,&$request, $id_vendor){
        $datas = ProjectPekerjaan::where('id_kategori',$item->id)
                                ->where('id_vendor',$id_vendor)
                                ->where('id_project',$request->id_project);

        if($request->has('sub_kategori') && !empty($request->sub_kategori)){
            $datas->where('id_subkategori',$request->id_subkategori);
        }

        if($request->has('nama_vendor') && !empty($request->nama_vendor)){
            $datas->where('id_vendor',$request->nama_vendor);
        }
        $datas = $datas->get();
        // if ($datas->isNotEmpty()) {
            $data[$item->name] = $datas;
        // }
    });
    return $data;
}

function formatTanggal ($tanggal = null)
{
    $date = Carbon::now();

    if(!empty($tanggal)){
        $formattedDate = $tanggal;
    }else{
        $formattedDate = $date->format('d F Y');
    }

    $bulan = [
        'January' => 'JANUARI',
        'February' => 'FEBRUARI',
        'March' => 'MARET',
        'April' => 'APRIL',
        'May' => 'MEI',
        'June' => 'JUNI',
        'July' => 'JULI',
        'August' => 'AGUSTUS',
        'September' => 'SEPTEMBER',
        'October' => 'OKTOBER',
        'November' => 'NOVEMBER',
        'December' => 'DESEMBER',
    ];

    $formattedDate = strtr($formattedDate, $bulan);
    return $formattedDate;
}

function generateBarcodeNumber() {
    $number = mt_rand(1000000000, 9999999999);

    if (barcodeNumberExists($number)) {
        return generateBarcodeNumber();
    }

    return $number;
}

function barcodeNumberExists($number) {
    return ProjectPekerjaan::where('kode_unik',$number)->exists();
}

function getProgressPekerjaan($id_vendor, $id)
{
    $progress = ProjectPekerjaan::where('id_project',$id)
    ->where('id_vendor',$id_vendor)
    ->whereNotNull('id_pekerjaan')
    ->select('id_vendor')
    ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
    ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
    ->groupBy('id_vendor')
    ->first();
    return $progress;
}

function Can($permission)
{
    return Auth::user()->can($permission);
}
