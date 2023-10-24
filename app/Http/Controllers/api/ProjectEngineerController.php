<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OnRequest;
use App\Models\ProjectPekerjaan;
use App\Models\Kategori;
use App\Models\SubKategori;
use App\Models\Pekerjaan;
use App\Models\SettingPekerjaan;
use App\Models\BeforePhoto;
use App\Models\AfterPhoto;

class ProjectEngineerController extends Controller
{
    public function index(Request $request)
    {
        try{
            $data = OnRequest::with(['kapal','customer'])
                        ->filter($request)
                        ->where('pe_id_1',$request->pe_id)
                        ->where('status',1)
                        ->get();

            foreach ($data as $item) {
                $item['progress'] = getProgresProject($item->id) . ' / ' . getCompleteProject($item->id);
            }     

            return response()->json(['success' => true, 'message' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function detailPE(Request $request)
    {
        try{
            $data = OnRequest::with(['complaint'])->where('id',$request->id)
                    ->first();

            $pekerjaan = ProjectPekerjaan::where('id_project',$request->id)
                            ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                            ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                            ->first();

            $vendor = ProjectPekerjaan::where('id_project',$request->id)
                ->select('id_vendor')
                ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_status_1')
                ->selectRaw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as total_status_2')
                ->groupBy('status', 'id_vendor')
                ->get();

            if($pekerjaan->total_status_1){
                $pekerjaan = $pekerjaan->total_status_2 / $pekerjaan->total_status_1;
            }else{
                $pekerjaan = '0 / 0';
            }

            $data['lokasi_project'] = $data->lokasi->name ?? null;
            $data['project_manajer'] = $data->pm->karyawan->name ?? null;
            $data['pekerjaan'] = $pekerjaan;
            $data['nama_vendor'] = $data->vendor->name ?? null;
            $data['vendor'] = count($vendor);

            foreach($data->pm->pe as $value){
                $value['nama_karyawan'] =  $value->karyawan->name;
            } 
         
            return response()->json(['success' => true, 'message' => 'success', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
