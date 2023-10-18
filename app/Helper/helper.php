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
