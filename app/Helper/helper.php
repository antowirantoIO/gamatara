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

function getProgress($id, $idKategori)
{
    $pekerjaan = ProjectPekerjaan::where('id_project',$id)
                                ->where('id_kategori',$idKategori)
                                ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                                ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                                ->first();
    return $pekerjaan;
}
